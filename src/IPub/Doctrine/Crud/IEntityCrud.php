<?php
/**
 * IEntityCrud.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Crud
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Crud;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;

interface IEntityCrud 
{
	/**
	 * @return Crud\Create\EntityCreator
	 */
	public function getEntityCreator();

	/**
	 * @return Crud\Update\EntityUpdater
	 */
	public function getEntityUpdater();

	/**
	 * @return Crud\Delete\EntityDeleter
	 */
	public function getEntityDeleter();
}