<?php declare(strict_types = 1);

/**
 * EntityMapper.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\Mapping;

use Doctrine\Common;
use Doctrine\ORM;
use Doctrine\Persistence;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Helpers;
use IPub\DoctrineCrud\Mapping;
use Nette;
use Nette\Utils;
use phpDocumentor;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

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

	use Nette\SmartObject;

	/** @var Persistence\ManagerRegistry */
	private Persistence\ManagerRegistry $managerRegistry;

	/** @var Common\Annotations\Reader */
	private Common\Annotations\Reader $annotationReader;

	/**
	 * @param Common\Cache\Cache $cache
	 * @param Persistence\ManagerRegistry $managerRegistry
	 */
	public function __construct(
		Common\Cache\Cache $cache,
		Persistence\ManagerRegistry $managerRegistry
	) {
		$this->managerRegistry = $managerRegistry;
		$this->annotationReader = new Common\Annotations\PsrCachedReader(
			new Common\Annotations\AnnotationReader(),
			Common\Cache\Psr6\CacheAdapter::wrap($cache)
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws ReflectionException
	 */
	public function fillEntity(Utils\ArrayHash $values, Entities\IEntity $entity, bool $isNew = false): Entities\IEntity
	{
		$reflectionClass = new ReflectionClass(get_class($entity));

		// Hack for proxy classes...
		if ($reflectionClass->implementsInterface(Common\Proxy\Proxy::class)) {
			// ... we need to extract entity class name from proxy class
			$parentClass = $reflectionClass->getParentClass();

			$entityClass = $parentClass !== false ? $parentClass->getName() : get_class($entity);

		} else {
			$entityClass = get_class($entity);
		}

		$entityClassManager = $this->managerRegistry->getManagerForClass($entityClass);

		if ($entityClassManager === null) {
			throw new Exceptions\InvalidStateException(sprintf('Entity manager for entity %s could not be loaded', $entityClass));
		}

		/** @var ORM\Mapping\ClassMetadata $classMetadata */
		$classMetadata = $entityClassManager->getClassMetadata($entityClass);

		$reflectionProperties = [];

		try {
			$ref = new ReflectionClass($entityClass);

			foreach ($ref->getProperties() as $reflectionProperty) {
				$reflectionProperties[] = $reflectionProperty->getName();
			}

		} catch (ReflectionException $ex) {
			// Nothing to do here
		}

		foreach (array_unique(array_merge($reflectionProperties, $classMetadata->getFieldNames(), $classMetadata->getAssociationNames())) as $fieldName) {
			try {
				$propertyReflection = new ReflectionProperty($entityClass, $fieldName);

			} catch (ReflectionException $ex) {
				// Entity property is readonly
				continue;
			}

			/** @var Mapping\Annotation\Crud|null $crud */
			$crud = $this->annotationReader->getPropertyAnnotation($propertyReflection, Mapping\Annotation\Crud::class);

			if ($crud !== null) {
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

						$annotations = array_map((function ($annotation): string {
							return get_class($annotation);
						}), $propertyAnnotations);

						if (isset($value['entity']) && class_exists($value['entity'])) {
							$className = $value['entity'];

						} elseif (in_array(ORM\Mapping\OneToOne::class, $annotations, true)) {
							$propertyAnnotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, ORM\Mapping\OneToOne::class);
							$className = is_object($propertyAnnotation) ? $propertyAnnotation->targetEntity : null;

						} elseif (in_array(ORM\Mapping\OneToMany::class, $annotations, true)) {
							$propertyAnnotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, ORM\Mapping\OneToMany::class);
							$className = is_object($propertyAnnotation) ? $propertyAnnotation->targetEntity : null;

							$items = [];

							if ($className !== null && class_exists($className)) {
								$rc = new ReflectionClass($className);

								$subClassIdProperty = null;

								foreach ($rc->getProperties() as $subClassProperty) {
									$subClassPropertyAnnotations = $this->annotationReader->getPropertyAnnotations($subClassProperty);

									$subClassAnnotations = array_map((function ($annotation): string {
										return get_class($annotation);
									}), $subClassPropertyAnnotations);

									if (in_array(ORM\Mapping\Id::class, $subClassAnnotations, true)) {
										$subClassIdProperty = $subClassProperty->getName();

										break;
									}
								}

								/** @var Utils\ArrayHash $item */
								foreach ($value as $item) {
									$subEntity = null;

									$subClassName = $className;

									$rc = new ReflectionClass($className);

									if ($rc->isAbstract() && isset($item['entity']) && class_exists($item['entity'])) {
										$subClassName = $item['entity'];

										$rc = new ReflectionClass($subClassName);
									}

									if ($subClassIdProperty !== null && $item->offsetExists($subClassIdProperty)) {
										$entityClassManager = $this->managerRegistry->getManagerForClass($subClassName);

										$subEntity = $entityClassManager === null ? null : $entityClassManager
											->getRepository($subClassName)
											->find($item->offsetGet($subClassIdProperty));

										if ($subEntity !== null && $subEntity instanceof Entities\IEntity) {
											$subEntity = $this->fillEntity($item, $subEntity);
										}
									}

									if ($subEntity === null) {
										$constructor = $rc->getConstructor();

										if ($constructor !== null) {
											$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $item, ['parent_entity' => $entity])));

										} else {
											$subEntity = new $subClassName();
										}

										if ($subEntity instanceof Entities\IEntity) {
											$subEntity = $this->fillEntity(Utils\ArrayHash::from(array_merge((array) $item, [$this->findAttributeName($entity, $subClassName) => $entity])), $subEntity, true);
										}
									}

									$items[] = $subEntity;
								}

								$this->setFieldValue($classMetadata, $entity, $fieldName, $items);
							}

							continue;

						} elseif (in_array(ORM\Mapping\ManyToOne::class, $annotations, true)) {
							$propertyAnnotation = $this->annotationReader->getPropertyAnnotation($propertyReflection, ORM\Mapping\ManyToOne::class);
							$className = is_object($propertyAnnotation) ? $propertyAnnotation->targetEntity : null;

						} else {
							$varAnnotation = $this->parseAnnotation($propertyReflection, 'var');

							$className = null;

							if ($varAnnotation !== null && strpos($varAnnotation, '|') !== false) {
								foreach (explode('|', $varAnnotation) as $varAnnotationItem) {
									if (class_exists($varAnnotationItem)) {
										$className = $varAnnotationItem;
									}
								}
							}
						}

						// Check if class is callable
						if (is_string($className) && class_exists($className)) {
							$rc = new ReflectionClass($className);

							if ($rc->isAbstract() && isset($value['entity']) && class_exists($value['entity'])) {
								$className = $value['entity'];
								$rc = new ReflectionClass($value['entity']);
							}

							$constructor = $rc->getConstructor();

							if ($constructor !== null) {
								$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $value, ['parent_entity' => $entity])));

								$this->setFieldValue($classMetadata, $entity, $fieldName, $subEntity);

							} else {
								$this->setFieldValue($classMetadata, $entity, $fieldName, new $className());
							}

						} else {
							$this->setFieldValue($classMetadata, $entity, $fieldName, $value);
						}
					}

					$fieldValue = $classMetadata->getFieldValue($entity, $fieldName);

					// Check again if entity was created
					if ($fieldValue !== null && $fieldValue instanceof Entities\IEntity) {
						$this->setFieldValue($classMetadata, $entity, $fieldName, $this->fillEntity(Utils\ArrayHash::from((array) $value), $fieldValue, $isNew));
					}

				} else {
					$varAnnotation = $this->parseAnnotation($propertyReflection, 'var');

					$className = $varAnnotation;

					if ($varAnnotation !== null && strpos($varAnnotation, '|') !== false) {
						foreach (explode('|', $varAnnotation) as $varAnnotationItem) {
							if (class_exists($varAnnotationItem)) {
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
							$rc = new ReflectionClass($className);

							if ($rc->isAbstract() && isset($value['entity']) && class_exists($value['entity'])) {
								$className = $value['entity'];
								$rc = new ReflectionClass($value['entity']);
							}

							$constructor = $rc->getConstructor();

							if ($constructor !== null) {
								$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $value, ['parent_entity' => $entity])));

								$this->setFieldValue($classMetadata, $entity, $fieldName, $subEntity);

							} else {
								$this->setFieldValue($classMetadata, $entity, $fieldName, new $className());
							}

						} elseif (isset($value['entity']) && class_exists($value['entity'])) {
							$className = $value['entity'];
							$rc = new ReflectionClass($value['entity']);

							$constructor = $rc->getConstructor();

							if ($constructor !== null) {
								$subEntity = $rc->newInstanceArgs(Helpers::autowireArguments($constructor, array_merge((array) $value, ['parent_entity' => $entity])));

							} else {
								$subEntity = new $className();
							}

							if ($subEntity instanceof Entities\IEntity) {
								$this->setFieldValue($classMetadata, $entity, $fieldName, $this->fillEntity(Utils\ArrayHash::from(array_merge((array) $value, [$this->findAttributeName($entity, $className) => $entity])), $subEntity, true));
							}

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
	 * @param Entities\IEntity $entity
	 * @param string $className
	 *
	 * @return string
	 *
	 * @throws ReflectionException
	 */
	private function findAttributeName(Entities\IEntity $entity, string $className): string
	{
		if (!class_exists($className)) {
			throw new Exceptions\InvalidStateException(sprintf('Provided class name %s is not valid class', $className));
		}

		$rc = new ReflectionClass($className);

		foreach ($rc->getProperties() as $property) {
			$propertyAnnotations = $this->annotationReader->getPropertyAnnotations($property);

			$annotations = array_map((function ($annotation): string {
				return get_class($annotation);
			}), $propertyAnnotations);

			$propertyClassName = null;

			if (in_array(ORM\Mapping\OneToOne::class, $annotations, true)) {
				$relationAnnotation = $this->annotationReader->getPropertyAnnotation($property, ORM\Mapping\OneToOne::class);
				$propertyClassName = is_object($relationAnnotation) ? $relationAnnotation->targetEntity : null;

			} elseif (in_array(ORM\Mapping\OneToMany::class, $annotations, true)) {
				$relationAnnotation = $this->annotationReader->getPropertyAnnotation($property, ORM\Mapping\OneToMany::class);
				$propertyClassName = is_object($relationAnnotation) ? $relationAnnotation->targetEntity : null;

			} elseif (in_array(ORM\Mapping\ManyToOne::class, $annotations, true)) {
				$relationAnnotation = $this->annotationReader->getPropertyAnnotation($property, ORM\Mapping\ManyToOne::class);
				$propertyClassName = is_object($relationAnnotation) ? $relationAnnotation->targetEntity : null;
			}

			if (is_string($propertyClassName) && $entity instanceof $propertyClassName) {
				return $property->getName();
			}
		}

		return 'parent_entity';
	}

	/**
	 * @param ORM\Mapping\ClassMetadata $classMetadata
	 * @param Entities\IEntity $entity
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return void
	 */
	private function setFieldValue(
		ORM\Mapping\ClassMetadata $classMetadata,
		Entities\IEntity $entity,
		string $field,
		$value
	): void {
		$methodName = 'set' . ucfirst($field);

		if ($value instanceof Utils\ArrayHash) {
			$value = (array) $value;
		}

		try {
			$propertyReflection = new ReflectionMethod(get_class($entity), $methodName);

			if ($propertyReflection->isPublic()) {
				$callback = [$entity, $methodName];

				// Try to call state setter
				if (is_callable($callback)) {
					call_user_func_array($callback, [$value]);
				}

			} elseif (isset($classMetadata->reflFields[$field])) {
				// Fallback for missing setter
				$classMetadata->setFieldValue($entity, $field, $value);
			}

			// Fallback for missing setter
		} catch (ReflectionException $ex) {
			$classMetadata->setFieldValue($entity, $field, $value);
		}
	}

	/**
	 * @param Reflector $ref
	 * @param string $name
	 *
	 * @return string|null
	 */
	private function parseAnnotation(Reflector $ref, string $name): ?string
	{
		$factory = phpDocumentor\Reflection\DocBlockFactory::createInstance();

		if (!method_exists($ref, 'getDocComment')) {
			return null;
		}

		$docBlock = $factory->create($ref->getDocComment());

		foreach ($docBlock->getTags() as $tag) {
			if ($tag->getName() === $name) {
				return trim((string) $tag);
			}
		}

		return null;
	}

}
