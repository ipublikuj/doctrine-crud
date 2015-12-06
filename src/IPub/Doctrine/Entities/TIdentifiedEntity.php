<?php
/**
 * TIdentifiedEntity.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	common
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Entities;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 *
 * @property-read int $id
 */
trait TIdentifiedEntity
{
	/**
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = (int) $id;

		return $this;
	}

	/**
	 * @return int
	 */
	final public function getId()
	{
		return $this->id;
	}

	public function __clone()
	{
		$this->id = NULL;
	}
}