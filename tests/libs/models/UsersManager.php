<?php declare(strict_types = 1);

namespace Tests\Cases\Models;

use DateTime;
use IPub\DoctrineCrud\Crud;
use Nette;
use Nette\Utils;

class UsersManager
{

	use Nette\SmartObject;

	/**
	 * @var Crud\IEntityCrud
	 */
	private Crud\IEntityCrud $entityCrud;

	/**
	 * @param Crud\IEntityCrud $entityCrud
	 */
	public function __construct(
		Crud\IEntityCrud $entityCrud
	) {
		// Entity CRUD for handling entities
		$this->entityCrud = $entityCrud;
	}

	/**
	 * @param Utils\ArrayHash $values
	 * @param UserEntity|null $entity
	 *
	 * @return UserEntity
	 */
	public function create(Utils\ArrayHash $values, ?UserEntity $entity = null): UserEntity
	{
		// Get entity creator
		$creator = $this->entityCrud->getEntityCreator();

		// Assign before create entity events
		$creator->beforeAction[] = function (UserEntity $entity, Utils\ArrayHash $values): void {
			$entity->setCreatedAt(new DateTime());
		};

		// Assign after create entity events
		$creator->afterAction[] = function (UserEntity $entity, Utils\ArrayHash $values): void {
		};

		// Create new entity
		return $creator->create($values, $entity);
	}

	/**
	 * @param UserEntity|mixed $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return UserEntity
	 */
	public function update(UserEntity $entity, Utils\ArrayHash $values): UserEntity
	{
		// Get entity updater
		$updater = $this->entityCrud->getEntityUpdater();

		// Assign before update entity events
		$updater->beforeAction[] = function (UserEntity $entity, Utils\ArrayHash $values): void {
			$entity->setUpdatedAt(new DateTime());
		};

		// Assign after create entity events
		$updater->afterAction[] = function (UserEntity $entity, Utils\ArrayHash $values): void {
		};

		// Update entity in database
		return $updater->update($values, $entity);
	}

	/**
	 * @param UserEntity|mixed $entity
	 *
	 * @return bool
	 */
	public function delete(UserEntity $entity): bool
	{
		// Get entity deleter
		$deleter = $this->entityCrud->getEntityDeleter();

		// Assign before delete entity events
		$deleter->beforeAction[] = function (UserEntity $entity): void {
		};

		// Assign after delete entity events
		$deleter->afterAction[] = function (): void {
		};

		// Delete entity from database
		return $deleter->delete($entity);
	}

	/**
	 * @return Crud\IEntityCrud
	 */
	public function getEntityCrud(): Crud\IEntityCrud
	{
		return $this->entityCrud;
	}

}
