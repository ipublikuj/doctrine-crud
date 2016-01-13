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

namespace IPub\Doctrine\Entities;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

/**
 * Doctrine CRUD identified entity helper trait
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @ORM\MappedSuperclass
 *
 * @property-read int $id
 */
trait TIdentifiedEntity
{
	use TEntity;

	/**
	 * @param mixed $id
	 *
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	final public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->id;
	}
}
