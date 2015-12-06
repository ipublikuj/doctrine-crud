<?php
/**
 * IEntity.php
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

interface IEntity
{
	/**
	 * @return array
	 */
	public function toArray();

	/**
	 * @return array
	 */
	public function toSimpleArray();

	/**
	 * @return string
	 */
	public function __toString();
}