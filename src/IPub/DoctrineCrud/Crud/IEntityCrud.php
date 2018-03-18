<?php
/**
 * IEntityCrud.php
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

namespace IPub\DoctrineCrud\Crud;

use IPub\DoctrineCrud\Crud;

/**
 * Doctrine CRUD interface
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityCrud
{
	/**
	 * @return Crud\Create\EntityCreator
	 */
	public function getEntityCreator() : Crud\Create\EntityCreator;

	/**
	 * @return Crud\Update\EntityUpdater
	 */
	public function getEntityUpdater() : Crud\Update\EntityUpdater;

	/**
	 * @return Crud\Delete\EntityDeleter
	 */
	public function getEntityDeleter() : Crud\Delete\EntityDeleter;
}
