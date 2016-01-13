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

namespace IPub\Doctrine\Crud;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;

/**
 * Doctrine CRUD interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IEntityCrud
{
	/**
	 * @return Crud\Create\EntityCreator
	 */
	function getEntityCreator();

	/**
	 * @return Crud\Update\EntityUpdater
	 */
	function getEntityUpdater();

	/**
	 * @return Crud\Delete\EntityDeleter
	 */
	function getEntityDeleter();
}
