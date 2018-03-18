<?php
/**
 * IIdentifiedEntity.php
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
 * Doctrine CRUD identified entity interface
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IIdentifiedEntity extends IEntity
{
	/**
	 * @return mixed
	 */
	public function getId();

	/**
	 * @return mixed
	 */
	public function getRawId();
}
