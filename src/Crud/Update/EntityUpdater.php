<?php declare(strict_types = 1);

/**
 * EntityUpdater.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\Crud\Update;

use Doctrine\Persistence;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Mapping;
use Nette\Utils;

/**
 * Doctrine CRUD entity updater
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method beforeAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 * @method afterAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 *
 * @phpstan-template   TEntityClass of \IPub\DoctrineCrud\Entities\IEntity
 * @phpstan-extends    Crud\CrudManager<TEntityClass>
 */
class EntityUpdater extends Crud\CrudManager
{

	/** @var Mapping\IEntityMapper */
	private Mapping\IEntityMapper $entityMapper;

	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Persistence\ManagerRegistry $managerRegistry
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName
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
	 * @param Entities\IEntity|int|string $entity
	 *
	 * @return Entities\IEntity
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function update(Utils\ArrayHash $values, $entity): Entities\IEntity
	{
		if (!$entity instanceof Entities\IEntity) {
			$entity = $this->entityRepository->find($entity);
		}

		if (!$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgumentException('Entity not found.');
		}

		$this->processHooks($this->beforeAction, [$entity, $values]);

		$this->entityMapper->fillEntity($values, $entity, false);

		$this->entityManager->persist($entity);

		$this->processHooks($this->afterAction, [$entity, $values]);

		if ($this->getFlush() === true) {
			$this->entityManager->flush();
		}

		return $entity;
	}

}
