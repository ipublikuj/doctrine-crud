<?php
/**
 * IEntity.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Entities
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine\Entities;

/**
 * Doctrine CRUD base entity interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IEntity
{
	/**
	 * @return array
	 */
	function toArray();

	/**
	 * @return array
	 */
	function toSimpleArray();

	/**
	 * @return string
	 */
	function __toString();
}
