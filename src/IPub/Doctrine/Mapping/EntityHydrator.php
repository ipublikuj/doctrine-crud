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

use Doctrine\Common;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;

use Nette;

class EntityHydrator extends Nette\Object implements IEntityHydrator
{
	/**
	 * {@inheritdoc}
	 */
	public function hydrate($values, Entities\IEntity $entity)
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
	 * {@inheritdoc}
	 */
	public function extract(Entities\IEntity $entity)
	{
		$details = [];

		if ($entity instanceof Doctrine\IIdentifiedEntity) {
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
	 * {@inheritdoc}
	 */
	public function simpleExtract(Entities\IEntity $entity)
	{
		$details = [];

		if ($entity instanceof Doctrine\IIdentifiedEntity) {
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
	 * @param Entities\IEntity $entity
	 *
	 * @return Nette\Reflection\Property[]
	 */
	protected function getEntityProperties(Entities\IEntity $entity)
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
		if ($value instanceof Entities\IEntity) {
			$value = $this->extract($value);

		} else if ($value instanceof Common\Collections\Collection) {
			$value = array_map(function ($entity) {
				if ($entity instanceof Entities\IEntity) {
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
		if ($value instanceof Doctrine\IIdentifiedEntity) {
			$value = $value->getId();

		} else if ($value instanceof Common\Collections\Collection) {
			$value = array_map(function ($entity) {
				if ($entity instanceof Doctrine\IIdentifiedEntity) {
					$entity = $entity->getId();
				}

				return $entity;
			}, $value->toArray());
		}

		return $value;
	}
}