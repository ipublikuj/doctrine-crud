<?php
/**
 * Test: IPub\DoctrineCrud\Models
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

namespace IPubTests\DoctrineCrud\Models;

use Nette;
use Nette\Utils;

use IPub;
use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Crud;

class UsersManager
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var Crud\IEntityCrud
	 */
	private $entityCrud;

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
	 * @param UserEntity|NULL $entity
	 *
	 * @return UserEntity
	 */
	public function create(Utils\ArrayHash $values, $entity = NULL)
	{
		// Get entity creator
		$creator = $this->entityCrud->getEntityCreator();

		// Assign before create entity events
		$creator->beforeAction[] = function (UserEntity $entity, Utils\ArrayHash $values) {
			$entity->setCreatedAt(new \DateTime);
		};

		// Assign after create entity events
		$creator->afterAction[] = function (UserEntity $entity, Utils\ArrayHash $values) {
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
	public function update($entity, Utils\ArrayHash $values)
	{
		// Get entity updater
		$updater = $this->entityCrud->getEntityUpdater();

		// Assign before update entity events
		$updater->beforeAction[] = function (UserEntity $entity, Utils\ArrayHash $values) {
			$entity->setUpdatedAt(new \DateTime);
		};

		// Assign after create entity events
		$updater->afterAction[] = function (UserEntity $entity, Utils\ArrayHash $values) {
		};

		// Update entity in database
		return $updater->update($values, $entity);
	}

	/**
	 * @param UserEntity|mixed $entity
	 *
	 * @return bool
	 */
	public function delete($entity)
	{
		// Get entity deleter
		$deleter = $this->entityCrud->getEntityDeleter();

		// Assign before delete entity events
		$deleter->beforeAction[] = function (UserEntity $entity) {
		};

		// Assign after delete entity events
		$deleter->afterAction[] = function () {
		};

		// Delete entity from database
		return $deleter->delete($entity);
	}

	/**
	 * @return Crud\IEntityCrud
	 */
	public function getEntityCrud()
	{
		return $this->entityCrud;
	}
}
