<?php
/**
 * EntityHydrator.php
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

use Doctrine\Common\Collections\Collection;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\IdentifiedEntity;

use Nette\Object;

class EntityHydrator extends Object implements IEntityHydrator
{
	/**
	 * @param $values
	 * @param Doctrine\IEntity $entity
	 *
	 * @return Doctrine\IEntity
	 */
	public function hydrate($values, Doctrine\IEntity $entity)
	{
		if (count($values)) {
			foreach ($values as $key => $value) {
				if (isset($entity->$key)) {
					$entity->$key = $value;
				}
			}
		}

		return $entity;
	}

	/**
	 * @param Doctrine\IEntity $entity
	 *
	 * @return array
	 */
	public function extract(Doctrine\IEntity &$entity)
	{
		$details = array();

		if ($entity instanceof IdentifiedEntity) {
			$details['id'] = $entity->getId();
		}

		$properties = $this->getEntityProperties($entity);

		foreach ($properties as $property) {
			if (!$property->isStatic()) {
				$value = $entity->{$property->getName()};
				$details[$property->getName()] = $this->extractor($value);
			}
		}

		return $details;
	}

	/**
	 * @param Doctrine\IEntity $entity
	 *
	 * @return array
	 */
	public function simpleExtract(Doctrine\IEntity &$entity)
	{
		$details = array();

		if ($entity instanceof IdentifiedEntity) {
			$details['id'] = $entity->getId();
		}

		$properties = $this->getEntityProperties($entity);

		foreach ($properties as $property) {
			if (!$property->isStatic()) {
				$value = $entity->{$property->getName()};
				$details[$property->getName()] = $this->simpleExtractor($value);
			}
		}

		return $details;
	}

	/**
	 * @param Doctrine\IEntity $entity
	 *
	 * @return \Nette\Reflection\Property[]
	 */
	protected function getEntityProperties(Doctrine\IEntity &$entity)
	{
		return $entity->getReflection()->getProperties(\ReflectionProperty::IS_PROTECTED);
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	protected function extractor($value)
	{
		if ($value instanceof Doctrine\IEntity) {
			$value = $this->extract($value);

		} else if ($value instanceof Collection) {
			$value = array_map(function ($entity) {
				if ($entity instanceof Doctrine\IEntity) {
					$entity = $this->simpleExtract($entity);
				}

				return $entity;
			}, $value->toArray());
		}

		return $value;
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	protected function simpleExtractor($value)
	{
		if ($value instanceof IdentifiedEntity) {
			$value = $value->getId();

		} else if ($value instanceof Collection) {
			$value = array_map(function ($entity) {
				if ($entity instanceof IdentifiedEntity) {
					$entity = $entity->getId();
				}

				return $entity;
			}, $value->toArray());
		}

		return $value;
	}
}