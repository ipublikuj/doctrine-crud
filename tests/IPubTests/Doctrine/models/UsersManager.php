<?php
/**
 * Test: IPub\Doctrine\Models
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

namespace IPubTests\Doctrine\Models;

use Nette;
use Nette\Utils;
use Nette\Security as NS;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;

class UsersManager extends Nette\Object
{
	/**
	 * @var NS\User
	 */
	private $user;

	/**
	 * @var Crud\IEntityCrud
	 */
	private $entityCrud;

	/**
	 * @param Crud\IEntityCrud $entityCrud
	 * @param NS\User $user
	 */
	function __construct(
		Crud\IEntityCrud $entityCrud,
		NS\User $user
	) {
		// Entity CRUD for handling entities
		$this->entityCrud = $entityCrud;

		// Get logged in user
		$this->user = $user;
	}

	/**
	 * @param Utils\ArrayHash $values
	 *
	 * @return UserEntity
	 */
	public function create(Utils\ArrayHash $values)
	{
		// Get entity creator
		$creator = $this->entityCrud->getEntityCreator();

		// Assign before create entity events
		$creator->beforeCreate[] = function (UserEntity $entity, Utils\ArrayHash $values) {
			$entity->setCreatedAt(new \DateTime);
		};

		// Assign after create entity events
		$creator->afterCreate[] = function (UserEntity $entity, Utils\ArrayHash $values) {
		};

		// Create new entity
		return $creator->create($values);
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
		$updater->beforeUpdate[] = function (UserEntity $entity, Utils\ArrayHash $values) {
		};

		// Assign after create entity events
		$updater->afterUpdate[] = function (UserEntity $entity, Utils\ArrayHash $values) {
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
		$deleter->beforeDelete[] = function (UserEntity $entity) {
		};

		// Assign after delete entity events
		$deleter->afterDelete[] = function () {
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
