<?php
/**
 * EntityMapper.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Mapping
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Mapping;

use IPub\Doctrine\DI\IContext;

use Kdyby\Doctrine\Entities\BaseEntity;

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
			if ( $property->hasAnnotation('required') && !isset($values[$property->name]) ) {
				throw new InvalidStateException('Missing required key "' . $property->name . '"');
			}

			if ( !isset($values[$property->name]) && (!$property->hasAnnotation('writable') || !$property->hasAnnotation('required')) ) {
				continue;
			}

			$value = $values[$property->name];

			if ( $value !== NULL ) {
				$parsedValues[$property->name] = $this->validateProperty($property, $value);
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
			if ( !isset($values[$property->name]) || !$property->hasAnnotation('writable') ) {
				continue;
			}

			$value = $values[$property->name];

			if ( $value !== NULL ) {
				$parsedValues[$property->name] = $this->validateProperty($property, $value);
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
		if ( $validatorClass = $property->getAnnotation('validator') ) {
			$value = $this->context->getValidator($validatorClass)->validate($value);
		}

		return $value;
	}
}