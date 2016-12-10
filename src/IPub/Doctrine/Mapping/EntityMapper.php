<?php
/**
 * EntityMapper.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Mapping
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\Doctrine\Mapping;

use Doctrine\Common;
use Doctrine\ORM;

use Nette;
use Nette\Reflection;
use Nette\Utils;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;
use IPub\Doctrine\Validation;

/**
 * Doctrine CRUD entity mapper
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityMapper extends Nette\Object implements IEntityMapper
{
	/**
	 * @var Validation\IValidator
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
	public function fillEntity(Utils\ArrayHash $values, Entities\IEntity $entity, $isNew = FALSE) : Entities\IEntity
	{
		$reflectionClass = new Reflection\ClassType(get_class($entity));

		// Hack for proxy classes...
		if ($reflectionClass->implementsInterface(Common\Proxy\Proxy::class)) {
			// ... we need to extract entity class name from proxy class
			$entityClass = $reflectionClass->getParentClass()->getName();

		} else {
			$entityClass = get_class($entity);
		}

		$classMetadata = $this->managerRegistry->getManagerForClass($entityClass)->getClassMetadata($entityClass);

		foreach (array_merge($classMetadata->getFieldNames(), $classMetadata->getAssociationNames()) as $fieldName) {

			try {
				$propertyReflection = new Nette\Reflection\Property($entityClass, $fieldName);

			} catch (\ReflectionException $ex) {
				// Entity property is readonly
				continue;
			}

			/** @var Doctrine\Mapping\Annotation\Crud $crud */
			if ($crud = $this->annotationReader->getPropertyAnnotation($propertyReflection, Doctrine\Mapping\Annotation\Crud::class)) {
				if ($isNew && $crud->isRequired() && !$values->offsetExists($fieldName)) {
					throw new Exceptions\InvalidStateException('Missing required key "' . $fieldName . '"');
				}

				if (!$values->offsetExists($fieldName) || (!$isNew && !$crud->isWritable())) {
					continue;
				}

				$value = $values->offsetGet($fieldName);

				if ($value instanceof Utils\ArrayHash || is_array($value)) {
					if (!$classMetadata->getFieldValue($entity, $fieldName) instanceof Entities\IEntity) {
						$propertyAnnotations = $this->annotationReader->getPropertyAnnotations($propertyReflection);

						$annotations = array_map((function ($annotation) {
							return get_class($annotation);
						}), $propertyAnnotations);

						if (in_array('Doctrine\ORM\Mapping\OneToOne', $annotations, TRUE)) {
							$className = $this->annotationReader->getPropertyAnnotation($propertyReflection, 'Doctrine\ORM\Mapping\OneToOne')->targetEntity;

						} elseif (in_array('Doctrine\ORM\Mapping\ManyToOne', $annotations, TRUE)) {
							$className = $this->annotationReader->getPropertyAnnotation($propertyReflection, 'Doctrine\ORM\Mapping\ManyToOne')->targetEntity;

						} else {
							$className = $propertyReflection->getAnnotation('var');
						}

						// Check if class is callable
						if (class_exists($className)) {
							$rc = new \ReflectionClass($className);

							if ($constructor = $rc->getConstructor()) {
								$subEntity = $rc->newInstanceArgs(Doctrine\Helpers::autowireArguments($constructor, array_merge((array) $value, [$entity])));

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

				} elseif ($this->validators->validate($value, $propertyReflection)) {

					$this->setFieldValue($classMetadata, $entity, $fieldName, $value);
				}
			}
		}

		return $entity;
	}

	/**
	 * @param Common\Persistence\Mapping\ClassMetadata $classMetadata
	 * @param Entities\IEntity $entity
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return void
	 */
	private function setFieldValue(Common\Persistence\Mapping\ClassMetadata $classMetadata, Entities\IEntity $entity, $field, $value)
	{
		$methodName = 'set' . ucfirst($field);

		// Try to call entity setter
		if (method_exists($entity, $methodName)) {
			call_user_func_array([$entity, $methodName], [$value]);

		// Fallback for missing setter
		} else {
			$classMetadata->setFieldValue($entity, $field, $value);
		}
	}
}
