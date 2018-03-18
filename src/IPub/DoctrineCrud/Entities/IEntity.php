<?php
/**
 * IEntity.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Entities
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Entities;

/**
 * Doctrine CRUD base entity interface
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntity
{
	/**
	 * @param int $maxLevel
	 *
	 * @return array
	 */
	public function toArray(int $maxLevel = 1) : array;

	/**
	 * @return array
	 */
	public function toSimpleArray() : array;

	/**
	 * @return string
	 */
	public function __toString();
}
