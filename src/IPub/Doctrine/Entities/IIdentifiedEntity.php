<?php
/**
 * IIdentifiedEntity.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Entities
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Entities;

interface IIdentifiedEntity
{
	/**
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setId($id);

	/**
	 * @return int
	 */
	public function getId();
}