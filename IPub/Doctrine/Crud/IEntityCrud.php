<?php
/**
 * IEntityCrud.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Crud
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Crud;

interface IEntityCrud 
{
	/**
	 * @return \IPub\Doctrine\EntityDao
	 */
	public function getEntityReader();

	/**
	 * @return \IPub\Doctrine\Crud\Delete\EntityDeleter
	 */
	public function getEntityDeleter();

	/**
	 * @return \IPub\Doctrine\Crud\Update\EntityUpdater
	 */
	public function getEntityUpdater();

	/**
	 * @return \IPub\Doctrine\Crud\Create\EntityCreator
	 */
	public function getEntityCreator();
}