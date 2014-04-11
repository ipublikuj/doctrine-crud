<?php
/**
 * IEntityUpdaterFactory.php
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

namespace IPub\Doctrine\Crud\Update;

use IPub\Doctrine\EntityDao;

interface IEntityUpdaterFactory
{
	/**
	 * @param EntityDao $dao
	 *
	 * @return IEntityUpdater
	 */
	public function createEntityUpdater(EntityDao $dao);
}