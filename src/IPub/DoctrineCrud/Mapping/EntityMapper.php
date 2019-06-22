<?php
/**
 * EntityMapper.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Mapping;

use Doctrine\Common;
use Doctrine\ORM;

use Nette;
use Nette\Reflection;
use Nette\Utils;

use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Helpers;
use IPub\DoctrineCrud\Mapping;
use IPub\DoctrineCrud\Validation;
use Tracy\Debugger;

/**
 * Doctrine CRUD entity mapper
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityMapper implements IEntityMapper
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var Validation\ValidatorProxy|Validation\IValidator
	 */
	private $validators;

	/**
	 * @var Common\Persistence\ManagerRegistry
	 */
	private $managerRegistry;

	/**
	 * @var Common\Annotations\AnnotationReader
	 */
	private $annotationReader;

	/**
	 * @param Validation\IValidator $validators
	 * @param Common\Annotations\Reader $annotationReader
	 * @param Common\Persistence\ManagerRegistry $managerRegistry
	 */
	public function __construct(
		Validation\IValidator $validators,
		Common\Annotations\Reader $annotationReader,
		Common\Persistence\ManagerRegistry $managerRegistry
	) {
		$this->validators = $validators;
		$this->annotationReader = $annotationReader;
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritdoc}
	 */
	public function fillEntity(Utils\ArrayHash $values, Entities\IEntity $entity, bool $isNew = FALSE) : Entities\IEntity
	{
		$reflectionClass = new Reflection\ClassType(get_class($entity));

		// Hack for proxy classes...
		if ($reflectionClass->implementsInterface(Common\Proxy\Proxy::class)) {
			// ... we need to extract entity class name from proxy class
			$entityClass = $reflectionClass->getParentClass()->getName();

		} else {
			$entityClass = get_class($entity);
		}

		/** @var ORM\Mapping\ClassMetadata $classMetadata */
		$classMetadata = $this->managerRegistry->getManagerForClass($entityClass)->getClassMetadata($entityClass);

		$reflectionProperties = [];

		try {
			$ref = new \ReflectionClass($entityClass);

			foreach ($ref->getProperties() as $reflectionProperty) {
				$reflectionProperties[] = $reflectionProperty->getName();
			}

		} catch (\ReflectionException $ex) {
			// Nothing to do here
		}

		foreach (array_unique(array_merge($reflectionProperties, $classMetadata->getFieldNames(), $classMetadata->getAssociationNames())) as $fieldName) {

			try {
				$propertyReflection = new Nette\Reflection\Property($entityClass, $fieldName);

			} catch (\ReflectionException $ex) {
				// Entity property is readonly
				continue;
			}

			/** @var Mapping\Annotation\Crud $crud */
			if ($crud = $this->annotationReader->getPropertyAnnotation($propertyReflection, Mapping\Annotation\Crud::class)) {
				if ($isNew && $crud->isRequired() && !$values->offsetExists($fieldName)) {
					throw new Exceptions\MissingRequiredFieldException($entity, $fieldName, sprintf('Missing required key "%s"', $fieldName));
				}

				if (!array_key_exists($fieldName, (array) $values) || (!$isNew && !$crud->isWritable())) {
					continue;
				}

				$value = $values->offsetGet($fieldName);

				if (($value instanceof Utils\ArrayHash || is_array($value)) && isset($classMetadata->reflFields[$fieldName])) {
					if (!$classMetadata->getFieldValue($entity, $fieldName) instanceof Entities\IEntity) {
						$propertyAnnotations = $this->annotationReader->getPropertyAnnotations($propertyReflection);

						$annotations = array_map((function ($annotation) : string {
							return get_class($annotation);
						}), $propertyAnnotations);

						if (isset($value['entity']) && class_exists($value['entity'])) {
							$className = $value['entity'];

						} elseif (in_array(ORM\Mapping\OneToOne::class, $annotations, TRUE)) {
							$className = $this->annotationReader->getPropertyAnnotation($propertyReflection, ORM\Mapping\OneToOne::class)->targetEntity;

						} elseif (in_array(ORM\Mapping\OneToMany::class, $annotations, TRUE)) {
							$className = $this->annotationReader->getPropertyAnnotation($propertyReflection, ORM\Mapping\OneToMany::class)->targetEntity;

							$items = [];

							if (is_string($className) && class_exists($className) && ($value instanceof Utils\ArrayHash || is_array($value))) {
								$rc = new \ReflectionClass($className);

								$subClassIdProperty = NULL;

								foreach ($rc->getProperties() as $subClassProperty) {
									$subClassPropertyAnnotations = $this->annotationReader->getPropertyAnnotations($subClassProperty);

									$subClassAnnotations = array_map((function ($annotation) : string {
										return get_class($annotation);
									}), $subClassPropertyAnnotations);

									if (in_array(ORM\Mapping\Id::class, $subClassAnnotations, TRUE)) {
										$subClassIdProperty = $subClassProperty->getName();

										break;
									}
								}

								/** @var Utils\ArrayHash $item */
								foreach ($value as $item) {
									$subEntity = NULL;

									$subClassName = $className;

									$rc = new \ReflectionClass($className);

									if ($rc->isAbstract() && isset($item['entity']) && class_exists($item['entity'])) {
										$subClassName = $item['entity'];

										$rc = new \ReflectionClass($subClassName);
									}

									if ($subClassIdProperty !== NULL && $item->offsetExists($subClassIdProperty)) {
										$subEntity = $this->managerRegistry
											->getManagerForClass($subClassName)
											->getRepository($subClassName)
											->find($item->offsetGet($subClassIdProperty));

										if ($subEntity !== NULL) {
											$subEntity = $this->fillEntity($item, $subEntity);
										}
									}

									if ($subEntity === NULL) {
										if ($constructor = $rc->getConstructor()) {
											$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $item, ['parent_entity' => $entity])));

										} else {
											$subEntity = new $subClassName;
										}

										$subEntity = $this->fillEntity(Utils\ArrayHash::from(array_merge((array) $item, [$this->findAttributeName($entity, $subClassName) => $entity])), $subEntity, TRUE);
									}

									$items[] = $subEntity;
								}

								$this->setFieldValue($classMetadata, $entity, $fieldName, $items);
							}

							continue;

						} elseif (in_array(ORM\Mapping\ManyToOne::class, $annotations, TRUE)) {
							$className = $this->annotationReader->getPropertyAnnotation($propertyReflection, ORM\Mapping\ManyToOne::class)->targetEntity;

						} else {
							$varAnnotation = $propertyReflection->getAnnotation('var');

							$className = NULL;

							if (strpos($varAnnotation, '|') !== FALSE) {
								foreach (explode('|', $varAnnotation) as $varAnnotationItem) {
									if (is_string($varAnnotationItem) && class_exists($varAnnotationItem)) {
										$className = $varAnnotationItem;
									}
								}
							}
						}

						// Check if class is callable
						if (
							is_string($className)
							&& class_exists($className)
							&& ($value instanceof Utils\ArrayHash || is_array($value))
						) {
							$rc = new \ReflectionClass($className);

							if ($rc->isAbstract() && isset($value['entity']) && class_exists($value['entity'])) {
								$className = $value['entity'];
								$rc = new \ReflectionClass($value['entity']);
							}

							if ($constructor = $rc->getConstructor()) {
								$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $value, ['parent_entity' => $entity])));

								$this->setFieldValue($classMetadata, $entity, $fieldName, $subEntity);

							} else {
								$this->setFieldValue($classMetadata, $entity, $fieldName, new $className);
							}

						} else {
							$this->setFieldValue($classMetadata, $entity, $fieldName, $value);
						}
					}

					// Check again if entity was created
					if (($fieldValue = $classMetadata->getFieldValue($entity, $fieldName)) && $fieldValue instanceof Entities\IEntity) {
						$this->setFieldValue($classMetadata, $entity, $fieldName, $this->fillEntity(Utils\ArrayHash::from((array) $value), $fieldValue, $isNew));
					}

				} else {
					$varAnnotation = $propertyReflection->getAnnotation('var');

					$className = $varAnnotation;

					if (strpos($varAnnotation, '|') !== FALSE) {
						foreach (explode('|', $varAnnotation) as $varAnnotationItem) {
							if (is_string($varAnnotationItem) && class_exists($varAnnotationItem)) {
								$className = $varAnnotationItem;
							}
						}
					}

					// Check if class is callable
					if (
						is_string($className)
						&& ($value instanceof Utils\ArrayHash || is_array($value))
					) {
						if (class_exists($className)) {
							$rc = new \ReflectionClass($className);

							if ($rc->isAbstract() && isset($value['entity']) && class_exists($value['entity'])) {
								$className = $value['entity'];
								$rc = new \ReflectionClass($value['entity']);
							}

							if ($constructor = $rc->getConstructor()) {
								$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $value, ['parent_entity' => $entity])));

								$this->setFieldValue($classMetadata, $entity, $fieldName, $subEntity);

							} else {
								$this->setFieldValue($classMetadata, $entity, $fieldName, new $className);
							}

						} elseif (isset($value['entity']) && class_exists($value['entity'])) {
							$className = $value['entity'];
							$rc = new \ReflectionClass($value['entity']);

							if ($constructor = $rc->getConstructor()) {
								$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $value, ['parent_entity' => $entity])));


							} else {
								$subEntity = new $className;
							}

							$this->setFieldValue($classMetadata, $entity, $fieldName, $this->fillEntity(Utils\ArrayHash::from(array_merge((array) $value, [$this->findAttributeName($entity, $className) => $entity])), $subEntity, TRUE));

						} else {
							$this->setFieldValue($classMetadata, $entity, $fieldName, $value);
						}

					} else {
						$this->setFieldValue($classMetadata, $entity, $fieldName, $value);
					}
				}
			}
		}

		return $entity;
	}

	/**
	 * @param ORM\Mapping\ClassMetadata $classMetadata
	 * @param Entities\IEntity $entity
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return void
	 */
	private function setFieldValue(ORM\Mapping\ClassMetadata $classMetadata, Entities\IEntity $entity, string $field, $value) : void
	{
		$methodName = 'set' . ucfirst($field);

		if ($value instanceof Utils\ArrayHash) {
			$value = (array) $value;
		}

		try {
			$propertyReflection = new Nette\Reflection\Method(get_class($entity), $methodName);

			//if (!$this->validators->validate($value, $propertyReflection)) {
			//	// Validation fail
			//}

			if ($propertyReflection->isPublic()) {
				// Try to call entity setter
				call_user_func_array([$entity, $methodName], [$value]);

			} elseif (isset($classMetadata->reflFields[$field])) {
				// Fallback for missing setter
				$classMetadata->setFieldValue($entity, $field, $value);
			}

			// Fallback for missing setter
		} catch (\ReflectionException $ex) {
			$classMetadata->setFieldValue($entity, $field, $value);
		}
	}

	/**
	 * @param Entities\IEntity $entity
	 * @param string $className
	 *
	 * @return string
	 */
	private function findAttributeName(Entities\IEntity $entity, string $className) : string
	{
		$rc = new \ReflectionClass($className);

		foreach ($rc->getProperties() as $property) {
			$propertyAnnotations = $this->annotationReader->getPropertyAnnotations($property);

			$annotations = array_map((function ($annotation) : string {
				return get_class($annotation);
			}), $propertyAnnotations);

			$propertyClassName = NULL;

			if (in_array(ORM\Mapping\OneToOne::class, $annotations, TRUE)) {
				$propertyClassName = $this->annotationReader->getPropertyAnnotation($property, ORM\Mapping\OneToOne::class)->targetEntity;

			} elseif (in_array(ORM\Mapping\OneToMany::class, $annotations, TRUE)) {
				$propertyClassName = $this->annotationReader->getPropertyAnnotation($property, ORM\Mapping\OneToMany::class)->targetEntity;

			} elseif (in_array(ORM\Mapping\ManyToOne::class, $annotations, TRUE)) {
				$propertyClassName = $this->annotationReader->getPropertyAnnotation($property, ORM\Mapping\ManyToOne::class)->targetEntity;
			}

			if (is_string($propertyClassName) && $entity instanceof $propertyClassName) {
				return $property->getName();
			}
		}

		return 'parent_entity';
	}
}
