<?php
/**
 * IEntityCrud.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Crud;

use IPub;
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
	function getEntityCreator() : Crud\Create\EntityCreator;

	/**
	 * @return Crud\Update\EntityUpdater
	 */
	function getEntityUpdater() : Crud\Update\EntityUpdater;

	/**
	 * @return Crud\Delete\EntityDeleter
	 */
	function getEntityDeleter() : Crud\Delete\EntityDeleter;
}