<?php
/**
 * IEntityCreatorFactory.php
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

namespace IPub\Doctrine\Crud\Create;

use IPub\Doctrine\EntityDao;

interface IEntityCreatorFactory
{
	/**
	 * @param EntityDao $dao
	 *
	 * @return IEntityCreator
	 */
	public function createEntityCreator(EntityDao $dao);
}