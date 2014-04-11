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

use IPub\ArrayHash;
use IPub\Doctrine\DI\IContext;

use Kdyby\Doctrine\Entities\BaseEntity;

use Nette\Diagnostics\Debugger;
use Nette\Object,
	Nette\InvalidStateException,
	Nette\Reflection\Property;

class EntityMapper extends Object implements IEntityMapper
{
	/**
	 * @var \IPub\Doctrine\DI\IContext
	 */
	private $context;

	/**
	 * @var \IPub\Doctrine\Mapping\IEntityHydrator
	 */
	private $entityMapper;

	/**
	 * @param IContext $context
	 * @param IEntityHydrator $entityMapper
	 */
	function __construct(IContext $context, IEntityHydrator $entityMapper)
	{
		$this->context = $context;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * @param $values
	 * @param BaseEntity $entity
	 *
	 * @return BaseEntity
	 */
	public function setValues($values, BaseEntity $entity)
	{
		return $this->entityMapper->hydrate($values, $entity);
	}

	/**
	 * @param BaseEntity $entity
	 *
	 * @return array
	 */
	public function getValues(BaseEntity &$entity)
	{
		return $this->entityMapper->extract($entity);
	}

	/**
	 * @param $values
	 * @param BaseEntity $entity
	 *
	 * @return BaseEntity
	 *
	 * @throws \Nette\InvalidStateException
	 */
	public function initValues($values, BaseEntity $entity)
	{
		$parsedValues = array();
		$properties = $entity->getReflection()->getProperties();

		foreach ($properties as $property) {
			if ($property->hasAnnotation('required') && !isset($values[$property->name])) {
				throw new InvalidStateException('Missing required key "' . $property->name . '"');
			}

			if (!isset($values[$property->name]) && (!$property->hasAnnotation('writable') || !$property->hasAnnotation('required'))) {
				continue;
			}

			$value = $values[$property->name];

			if ($value !== NULL) {
				if ($value instanceof \Nette\ArrayHash) {
					if (!$entity->{$property->name} instanceof BaseEntity) {
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
						if (!$entity->{$property->name} instanceof BaseEntity && class_exists($className)) {
							$entity->{$property->name} = new $className;

						} else {
							$entity->{$property->name} = $value;
						}
					}

					// Check again if entity was created
					if ($entity->{$property->name} instanceof BaseEntity) {
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
	 * @param $values
	 * @param BaseEntity $entity
	 *
	 * @return BaseEntity
	 */
	public function updateValues($values, BaseEntity $entity)
	{
		$parsedValues = array();
		$properties = $entity->getReflection()->getProperties();

		foreach ($properties as $property) {
			if (!isset($values[$property->name]) || !$property->hasAnnotation('writable')) {
				continue;
			}

			$value = $values[$property->name];

			if ($value !== NULL) {
				if ($value instanceof \Nette\ArrayHash) {
					if (!$entity->{$property->name} instanceof BaseEntity) {
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
						if (!$entity->{$property->name} instanceof BaseEntity && class_exists($className)) {
							$entity->{$property->name} = new $className;

						} else {
							$entity->{$property->name} = $value;
						}
					}

					// Check again if entity was created
					if ($entity->{$property->name} instanceof BaseEntity) {
						$parsedValues[$property->name] = $this->updateValues($value, $entity->{$property->name});
					}

				} else {
					$parsedValues[$property->name] = $this->validateProperty($property, $value);
				}
			}
		}

		return $this->setValues($parsedValues, $entity);
	}

	/**
	 * @param Property $property
	 * @param $value
	 *
	 * @return mixed
	 */
	private function validateProperty(Property $property, $value)
	{
		if ($validatorClass = $property->getAnnotation('validator')) {
			$value = $this->context->getValidator($validatorClass)->validate($value);
		}

		return $value;
	}
}