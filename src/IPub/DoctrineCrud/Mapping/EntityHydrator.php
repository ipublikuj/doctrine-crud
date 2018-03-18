<?php
/**
 * EntityHydrator.php
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

namespace IPub\DoctrineCrud\Mapping;

use Doctrine\Common;
use Doctrine\ORM;

use Nette;

use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Entities;

/**
 * Doctrine CRUD entity hydrator
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityHydrator implements IEntityHydrator
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

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
	public function hydrate($values, Entities\IEntity $entity) : Entities\IEntity
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
	public function extract(Entities\IEntity $entity, int $maxLevel = 1, int $level = 1) : array
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
	public function simpleExtract(Entities\IEntity $entity) : array
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
	 * @return mixed|mixed[]
	 */
	private function extractor($value, int $maxLevel = 1, int $level = 1)
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
	 * @return mixed|mixed[]
	 */
	private function simpleExtractor($value)
	{
		if ($value instanceof DoctrineCrud\Entities\IIdentifiedEntity) {
			$value = $value->getId();

		} else {
			if ($value instanceof Common\Collections\Collection) {
				$value = array_map(function ($entity) {
					if ($entity instanceof DoctrineCrud\Entities\IIdentifiedEntity) {
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
	 *
	 * @throws \ReflectionException
	 */
	private function getEntityProperties(Entities\IEntity $entity) : array
	{
		$entityReflection = new \ReflectionClass(get_class($entity));

		return $entityReflection->getProperties();
	}

	/**
	 * Create default annotation reader for extensions
	 *
	 * @return Common\Annotations\CachedReader
	 *
	 * @throws Common\Annotations\AnnotationException
	 */
	private function getDefaultAnnotationReader() : Common\Annotations\CachedReader
	{
		$reader = new Common\Annotations\AnnotationReader;

		Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
			'IPub\\Doctrine\\Entities\\IEntity'
		);

		$reader = new Common\Annotations\CachedReader($reader, new Common\Cache\ArrayCache);

		return $reader;
	}
}
