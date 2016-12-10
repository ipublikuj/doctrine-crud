<?php
/**
 * IEntityCrud.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\Doctrine\Crud;

use IPub;
use IPub\Doctrine\Crud;

/**
 * Doctrine CRUD interface
 *
 * @package        iPublikuj:Doctrine!
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
