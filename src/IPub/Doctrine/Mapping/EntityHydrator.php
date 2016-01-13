<?php
/**
 * EntityHydrator.php
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

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;

/**
 * Doctrine CRUD entity hydrator
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class EntityHydrator extends Nette\Object implements IEntityHydrator
{
	/**
	 * Property needs to have at least one of these annotations to be serialized
	 *
	 * @var array
	 */
	public static $mappedPropertyAnnotations = [
		ORM\Mapping\Column::class,
		ORM\Mapping\OneToMany::class,
		ORM\Mapping\OneToOne::class,
		ORM\Mapping\ManyToOne::class,
		ORM\Mapping\ManyToMany::class,
	];

	/**
	 * {@inheritdoc}
	 */
	public function hydrate($values, Entities\IEntity $entity)
	{
		if (count($values)) {
			foreach ($values as $name => $value) {
				$method = 'set' . ucfirst($name);

				if (method_exists($entity, $method)) {
					call_user_func_array([$entity, $method], [$value]);
				}
			}
		}

		return $entity;
	}

	/**
	 * {@inheritdoc}
	 */
	public function extract(Entities\IEntity $entity, $maxLevel = 1, $level = 1)
	{
		$values = [];

		$properties = $this->getEntityProperties($entity);
		$reader = $this->getDefaultAnnotationReader();

		foreach ($properties as $property) {
			if (!$property->isStatic()) {
				$propertyAnnotations = $reader->getPropertyAnnotations($property);

				foreach ($propertyAnnotations as $propertyAnnotation) {
					if (in_array(get_class($propertyAnnotation), self::$mappedPropertyAnnotations, TRUE)) {
						$method = 'get' . ucfirst($property->getName());

						if (method_exists($entity, $method)) {
							$value = call_user_func([$entity, $method]);
							$values[$property->getName()] = $this->extractor($value, $maxLevel, $level);
						}

						continue;
					}
				}
			}
		}

		return $values;
	}

	/**
	 * {@inheritdoc}
	 */
	public function simpleExtract(Entities\IEntity $entity)
	{
		$values = [];

		$properties = $this->getEntityProperties($entity);
		$reader = $this->getDefaultAnnotationReader();

		foreach ($properties as $property) {
			if (!$property->isStatic()) {
				$propertyAnnotations = $reader->getPropertyAnnotations($property);

				foreach ($propertyAnnotations as $propertyAnnotation) {
					if (in_array(get_class($propertyAnnotation), self::$mappedPropertyAnnotations, TRUE)) {
						$method = 'get' . ucfirst($property->getName());

						if (method_exists($entity, $method)) {
							$value = call_user_func([$entity, $method]);
							$values[$property->getName()] = $this->simpleExtractor($value);
						}

						continue;
					}
				}
			}
		}

		return $values;
	}

	/**
	 * @param mixed $value
	 * @param int $maxLevel
	 * @param int $level
	 *
	 * @return array
	 */
	private function extractor($value, $maxLevel = 1, $level = 1)
	{
		if ($value instanceof Entities\IEntity) {
			if ($level < $maxLevel) {
				$level++;

				$value = $this->extract($value, $maxLevel, $level);
			}

		} else {
			if ($value instanceof Common\Collections\Collection) {
				$value = array_map(function ($entity) use ($maxLevel, $level) {
					if ($entity instanceof Entities\IEntity && $level < $maxLevel) {
						$level++;

						$entity = $this->extract($entity, $maxLevel, $level);
					}

					return $entity;
				}, $value->toArray());
			}
		}

		return $value;
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	private function simpleExtractor($value)
	{
		if ($value instanceof Doctrine\Entities\IIdentifiedEntity) {
			$value = $value->getId();

		} else {
			if ($value instanceof Common\Collections\Collection) {
				$value = array_map(function ($entity) {
					if ($entity instanceof Doctrine\Entities\IIdentifiedEntity) {
						$entity = $entity->getId();
					}

					return $entity;
				}, $value->toArray());
			}
		}

		return $value;
	}

	/**
	 * @param Entities\IEntity $entity
	 *
	 * @return Nette\Reflection\Property[]
	 */
	private function getEntityProperties(Entities\IEntity $entity)
	{
		$entityReflection = new \ReflectionClass(get_class($entity));

		return $entityReflection->getProperties(\ReflectionProperty::IS_PROTECTED);
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
