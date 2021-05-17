<?php declare(strict_types = 1);

/**
 * IEntityCrud.php
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

namespace IPub\DoctrineCrud\Crud;

use IPub\DoctrineCrud\Crud;

/**
 * Doctrine CRUD interface
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @phpstan-template TEntityClass of \IPub\DoctrineCrud\Entities\IEntity
 */
interface IEntityCrud
{

	/**
	 * @return Crud\Create\EntityCreator
	 *
	 * @phpstan-return  Crud\Create\EntityCreator<TEntityClass>
	 */
	public function getEntityCreator(): Crud\Create\EntityCreator;

	/**
	 * @return Crud\Update\EntityUpdater
	 *
	 * @phpstan-return  Crud\Update\EntityUpdater<TEntityClass>
	 */
	public function getEntityUpdater(): Crud\Update\EntityUpdater;

	/**
	 * @return Crud\Delete\EntityDeleter
	 *
	 * @phpstan-return  Crud\Delete\EntityDeleter<TEntityClass>
	 */
	public function getEntityDeleter(): Crud\Delete\EntityDeleter;

}
