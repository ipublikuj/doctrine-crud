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

declare(strict_types = 1);

namespace IPub\Doctrine\Entities;

/**
 * Doctrine CRUD identified entity interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IIdentifiedEntity extends IEntity
{
	/**
	 * @return mixed
	 */
	function getId();

	/**
	 * @return mixed
	 */
	function getRawId();
}
