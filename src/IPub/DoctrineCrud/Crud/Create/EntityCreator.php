<?php
/**
 * EntityCreator.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Crud\Create;

use Doctrine\Common\Persistence;

use ReflectionClass;
use ReflectionException;

use Nette\Utils;

use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD entity creator
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method beforeAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 * @method afterAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 */
class EntityCreator extends Crud\CrudManager
{
	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Persistence\ManagerRegistry $managerRegistry
	 */
	public function __construct(
		string $entityName,
		Mapping\IEntityMapper $entityMapper,
		Persistence\ManagerRegistry $managerRegistry
	) {
		parent::__construct($entityName, $managerRegistry);

		$this->entityMapper = $entityMapper;
	}

	/**
	 * @param Utils\ArrayHash $values
	 * @param Entities\IEntity|NULL $entity
	 *
	 * @return Entities\IEntity
	 */
	public function create(Utils\ArrayHash $values, Entities\IEntity $entity = null): Entities\IEntity
	{
		if (!$entity instanceof Entities\IEntity) {
			try {
				// Entity name is overriden
				if ($values->offsetExists('entity') && class_exists($values->offsetGet('entity'))) {
					$entityClass = $values->offsetGet('entity');

				} else {
					$entityClass = $this->entityName;
				}

				$rc = new ReflectionClass($entityClass);

				if ($rc->isAbstract()) {
					throw new Exceptions\InvalidArgumentException(sprintf('Abstract entity "%s" can not be used.', $entityClass));
				}

				if ($constructor = $rc->getConstructor()) {
					$entity = $rc->newInstanceArgs(DoctrineCrud\Helpers::autowireArguments($constructor, (array) $values));

				} else {
					$entity = $this->entityManager->getClassMetadata($this->entityName)->newInstance();
				}

			} catch (ReflectionException $ex) {
				// Class could not be parsed
			}
		}

		if (!$entity || !$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgumentException('Entity could not be created.');
		}

		$this->processHooks($this->beforeAction, [$entity, $values]);

		$this->entityMapper->fillEntity($values, $entity, true);

		$this->entityManager->persist($entity);

		$this->processHooks($this->afterAction, [$entity, $values]);

		if ($this->getFlush()) {
			$this->entityManager->flush();
		}

		return $entity;
	}
}
