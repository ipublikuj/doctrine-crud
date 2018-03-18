<?php
/**
 * TIdentifiedEntity.php
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

use Doctrine\ORM\Mapping as ORM;

/**
 * Doctrine CRUD identified entity helper trait
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @ORM\MappedSuperclass
 *
 * @property-read int $id
 */
trait TIdentifiedEntity
{
	/**
	 * @return mixed
	 */
	public function getId()
	{
		return is_integer($this->id) ? $this->id : $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getRawId()
	{
		return $this->id;
	}
}
