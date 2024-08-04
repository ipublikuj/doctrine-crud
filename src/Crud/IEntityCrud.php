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
use IPub\DoctrineCrud\Entities;

/**
 * Doctrine CRUD interface
 *
 * @template T of Entities\IEntity
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityCrud
{

	/**
	 * @return  Crud\Create\EntityCreator<T>
	 */
	public function getEntityCreator(): Crud\Create\EntityCreator;

	/**
	 * @return  Crud\Update\EntityUpdater<T>
	 */
	public function getEntityUpdater(): Crud\Update\EntityUpdater;

	/**
	 * @return  Crud\Delete\EntityDeleter<T>
	 */
	public function getEntityDeleter(): Crud\Delete\EntityDeleter;

}
