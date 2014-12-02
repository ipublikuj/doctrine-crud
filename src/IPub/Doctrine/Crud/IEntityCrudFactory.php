<?php
/**
 * IEntityCrudFactory.php
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

interface IEntityCrudFactory
{
	/**
	 * @param $entityName
	 *
	 * @return IEntityCrud
	 */
	public function createEntityCrud($entityName);
}