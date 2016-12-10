<?php
/**
 * TIdentifiedEntity.php
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

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

/**
 * Doctrine CRUD identified entity helper trait
 *
 * @package        iPublikuj:Doctrine!
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
