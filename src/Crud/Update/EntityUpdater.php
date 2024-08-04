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

use Doctrine\DBAL;
use Doctrine\ORM;
use Doctrine\Persistence;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Mapping;
use Nette\Utils;

/**
 * Doctrine CRUD entity updater
 *
 * @template   T of Entities\IEntity
 * @extends    Crud\CrudManager<T>
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class EntityUpdater extends Crud\CrudManager
{

	/** @var array<callable(Entities\IEntity, Utils\ArrayHash): void> */
	public array $beforeAction = [];

	/** @var array<callable(Entities\IEntity, Utils\ArrayHash): void> */
	public array $afterAction = [];

	/**
	 * @param class-string<T> $entityName
	 */
	public function __construct(
		string $entityName,
		private readonly Mapping\IEntityMapper $entityMapper,
		Persistence\ManagerRegistry $managerRegistry,
	)
	{
		parent::__construct($entityName, $managerRegistry);
	}

	/**
	 * @throws Exceptions\InvalidArgument
	 * @throws Exceptions\InvalidState
	 */
	public function update(Utils\ArrayHash $values, Entities\IEntity|int|string $entity): Entities\IEntity
	{
		if (!$entity instanceof Entities\IEntity) {
			$entity = $this->entityRepository->find($entity);
		}

		if (!$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgument('Entity not found.');
		}

		Utils\Arrays::invoke($this->beforeAction, $entity, $values);

		$this->entityMapper->fillEntity($values, $entity, false);

		$this->entityManager->persist($entity);

		Utils\Arrays::invoke($this->afterAction, $entity, $values);

		if ($this->getFlush() === true) {
			try {
				$this->entityManager->flush();
			} catch (DBAL\Exception\UniqueConstraintViolationException | ORM\Exception\ORMException $ex) {
				throw new Exceptions\InvalidState('Entity could not be updated', $ex->getCode(), $ex);
			}
		}

		return $entity;
	}

}
