<?php
/**
 * Entity.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	common
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine;

use Doctrine\ORM\Proxy\Proxy,
	Doctrine\ORM\Mapping as ORM;

use Nette;

use Kdyby;

/**
 * @ORM\MappedSuperclass()
 *
 * @property-read int $id
 *
 * @deprecated
 */
abstract class IdentifiedEntity extends \Kdyby\Doctrine\Entities\BaseEntity
{
	/**
	 * @return integer
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