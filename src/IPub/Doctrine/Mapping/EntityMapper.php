<?php
/**
 * EntityMapper.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Mapping
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Mapping;

use Nette;
use Nette\Reflection;
use Nette\Utils;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;

class EntityMapper extends Nette\Object implements IEntityMapper
{
	/**
	 * @var Doctrine\Validators
	 */
	protected $validators;

	/**
	 * @var Doctrine\Mapping\IEntityHydrator
	 */
	protected $entityMapper;

	/**
	 * @param Doctrine\Validators $validators
	 * @param IEntityHydrator $entityMapper
	 */
	public function __construct(Doctrine\Validators $validators, IEntityHydrator $entityMapper)
	{
		$this->validators = $validators;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setValues($values, Entities\IEntity $entity)
	{
		return $this->entityMapper->hydrate($values, $entity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValues(Entities\IEntity &$entity)
	{
		return $this->entityMapper->extract($entity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function initValues($values, Entities\IEntity $entity)
	{
		$parsedValues = [];
		$properties = $entity->getReflection()->getProperties();

		foreach ($properties as $property) {
			if ($property->hasAnnotation('required') && !isset($values[$property->name])) {
				throw new Exceptions\InvalidStateException('Missing required key "' . $property->name . '"');
			}

			if (!array_key_exists($property->name, $values) || (!$property->hasAnnotation('writable') && !$property->hasAnnotation('required'))) {
				continue;
			}

			$value = $values[$property->name];

			if ($value !== NULL) {
				if ($value instanceof Utils\ArrayHash) {
					if (!$entity->{$property->name} instanceof Entities\IEntity) {
						$className = NULL;

						if ($property->hasAnnotation('ORM\OneToOne')) {
							$className = $property->getAnnotation('ORM\OneToOne')->targetEntity;

						} else if ($property->hasAnnotation('OneToOne')) {
							$className = $property->getAnnotation('OneToOne')->targetEntity;

						} else if ($property->hasAnnotation('ORM\ManyToOne')) {
							$className = $property->getAnnotation('ORM\ManyToOne')->targetEntity;

						} else if ($property->hasAnnotation('ManyToOne')) {
							$className = $property->getAnnotation('ManyToOne')->targetEntity;

						} else if ($property->hasAnnotation('var')) {
							$className = $property->getAnnotation('var');
						}

						// Check if class is callable
						if (!$entity->{$property->name} instanceof Entities\IEntity && class_exists($className)) {
							$entity->{$property->name} = new $className;

						} else {
							$entity->{$property->name} = $value;
						}
					}

					// Check again if entity was created
					if ($entity->{$property->name} instanceof Entities\IEntity) {
						$parsedValues[$property->name] = $this->initValues($value, $entity->{$property->name});
					}

				} else {
					$parsedValues[$property->name] = $this->validateProperty($property, $value);
				}
			}
		}

		return $this->setValues($parsedValues, $entity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function updateValues($values, Entities\IEntity $entity)
	{
		$parsedValues = [];
		$properties = $entity->getReflection()->getProperties();

		foreach ($properties as $property) {
			if (!array_key_exists($property->name, $values) || !$property->hasAnnotation('writable')) {
				continue;
			}

			$value = $values[$property->name];

				if ($value instanceof Utils\ArrayHash) {
					if (!$entity->{$property->name} instanceof Entities\IEntity) {
						$className = NULL;

						if ($property->hasAnnotation('ORM\OneToOne')) {
							$className = $property->getAnnotation('ORM\OneToOne')->targetEntity;

						} else if ($property->hasAnnotation('OneToOne')) {
							$className = $property->getAnnotation('OneToOne')->targetEntity;

						} else if ($property->hasAnnotation('ORM\ManyToOne')) {
							$className = $property->getAnnotation('ORM\ManyToOne')->targetEntity;

						} else if ($property->hasAnnotation('ManyToOne')) {
							$className = $property->getAnnotation('ManyToOne')->targetEntity;

						} else if ($property->hasAnnotation('var')) {
							$className = $property->getAnnotation('var');
						}

						// Check if class is callable
						if (!$entity->{$property->name} instanceof Entities\IEntity && class_exists($className)) {
							$entity->{$property->name} = new $className;

						} else {
							$entity->{$property->name} = $value;
						}
					}

					// Check again if entity was created
					if ($entity->{$property->name} instanceof Entities\IEntity) {
						$parsedValues[$property->name] = $this->updateValues($value, $entity->{$property->name});
					}

				} else {
					$parsedValues[$property->name] = $this->validateProperty($property, $value);
				}
		}

		return $this->setValues($parsedValues, $entity);
	}

	/**
	 * @param Reflection\Property $property
	 * @param $value
	 *
	 * @return mixed
	 */
	protected function validateProperty(Reflection\Property $property, $value)
	{
		// Check if property has validator and validator is registered
		if ($validatorClass = $property->getAnnotation('validator') AND $validator = $this->validators->getValidator($validatorClass)) {
			$value = $validator->validate($value);
		}

		return $value;
	}
}