<?php
/**
 * IEntityDeleterFactory.php
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

namespace IPub\Doctrine\Crud\Delete;

use IPub\Doctrine\EntityDao;

interface IEntityDeleterFactory
{
	/**
	 * @param EntityDao $dao
	 *
	 * @return IEntityDeleter
	 */
	public function createEntityDeleter(EntityDao $dao);
}