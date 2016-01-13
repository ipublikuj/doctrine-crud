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

/**
 * Doctrine CRUD entity mapper
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class EntityMapper extends Nette\Object implements IEntityMapper
{
	/**
	 * Annotation field is blameable
	 */
	const EXTENSION_ANNOTATION = 'IPub\Doctrine\Mapping\Annotation\Crud';

	/**
	 * Define class name
	 */
	const CLASS_NAME = __CLASS__;

	/**
	 * @var Doctrine\Validators
	 */
	private $validators;

	/**
	 * @var ORM\EntityManager
	 */
	private $entityManager;

	/**
	 * @var Common\Annotations\AnnotationReader
	 */
	private $annotationReader;

	/**
	 * @param Doctrine\Validators $validators
	 * @param ORM\EntityManager $entityManager
	 */
	public function __construct(Doctrine\Validators $validators, ORM\EntityManager $entityManager)
	{
		$this->validators = $validators;
		$this->entityManager = $entityManager;

		$this->annotationReader = $this->getDefaultAnnotationReader();
	}

	/**
	 * {@inheritdoc}
	 */
	public function fillEntity(Utils\ArrayHash $values, Entities\IEntity $entity, $isNew = FALSE)
	{
		$classMetadata = $this->entityManager->getClassMetadata(get_class($entity));

		foreach (array_merge($classMetadata->getFieldNames(), $classMetadata->getAssociationNames()) as $fieldName) {
			$propertyReflection = new Nette\Reflection\Property(get_class($entity), $fieldName);

			/** @var Doctrine\Mapping\Annotation\Crud $crud */
			if ($crud = $this->annotationReader->getPropertyAnnotation($propertyReflection, self::EXTENSION_ANNOTATION)) {
				if ($isNew && $crud->isRequired() && !$values->offsetExists($fieldName)) {
					throw new Exceptions\InvalidStateException('Missing required key "' . $fieldName . '"');
				}

				if (!$values->offsetExists($fieldName) || (!$isNew && !$crud->isWritable()) || ($isNew && !$crud->isRequired())) {
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
							$classMetadata->setFieldValue($entity, $fieldName, new $className);

						} else {
							$classMetadata->setFieldValue($entity, $fieldName, $value);
						}
					}

					// Check again if entity was created
					if (($fieldValue = $classMetadata->getFieldValue($entity, $fieldName)) && $fieldValue instanceof Entities\IEntity) {
						$classMetadata->setFieldValue($entity, $fieldName, $this->fillEntity(Utils\ArrayHash::from((array) $value), $fieldValue, $isNew));
					}

				} else {
					if ($crud->validator !== NULL) {
						$value = $this->validateProperty($crud->validator, $value);
					}

					$classMetadata->setFieldValue($entity, $fieldName, $value);
				}
			}
		}

		return $entity;
	}

	/**
	 * @param string $validatorClass
	 * @param $value
	 *
	 * @return mixed
	 */
	private function validateProperty($validatorClass, $value)
	{
		// Check if property has validator and validator is registered
		if ($validator = $this->validators->getValidator($validatorClass)) {
			$value = $validator->validate($value);
		}

		return $value;
	}

	/**
	 * Create default annotation reader for extensions
	 *
	 * @return Common\Annotations\AnnotationReader
	 */
	private function getDefaultAnnotationReader()
	{
		$reader = new Common\Annotations\AnnotationReader;

		Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
			'IPub\\Doctrine\\Entities\\IEntity'
		);

		$reader = new Common\Annotations\CachedReader($reader, new Common\Cache\ArrayCache);

		return $reader;
	}
}
