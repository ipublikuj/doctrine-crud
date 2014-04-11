<?php
/**
 * IEntityUpdater.php
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

interface IEntityUpdater
{
	/**
	 * @param bool $flush
	 *
	 * @return $this
	 */
	public function setFlush($flush);

	/**
	 * @param $values
	 * @param int|\IPub\Doctrine\Entity $entity
	 *
	 * @return \IPub\Doctrine\Entity
	 */
	public function update($entity, $values);
}