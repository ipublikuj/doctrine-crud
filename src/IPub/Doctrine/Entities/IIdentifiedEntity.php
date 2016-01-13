<?php
/**
 * IIdentifiedEntity.php
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
 * Doctrine CRUD identified entity interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IIdentifiedEntity
{
	/**
	 * @param mixed $id
	 */
	public function setId($id);

	/**
	 * @return mixed
	 */
	public function getId();
}
