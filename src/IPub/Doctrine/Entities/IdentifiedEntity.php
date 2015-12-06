<?php
/**
 * IdentifiedEntity.php
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

use Doctrine\ORM\Mapping as ORM;

use Nette;

use Kdyby;

/**
 * @ORM\MappedSuperclass
 *
 * @property-read int $id
 */
abstract class IdentifiedEntity extends Entity implements IIdentifiedEntity
{
	/**
	 * {@inheritdoc}
	 */
	final public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * {@inheritdoc}
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