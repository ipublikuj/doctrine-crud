<?php
/**
 * Test: IPub\Doctrine\Models
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

namespace IPubTests\Doctrine\Models;

use Doctrine\ORM\Mapping as ORM;

use IPub;
use IPub\Doctrine\Entities;

use IPub\Doctrine\Mapping\Annotation as IPubDoctrine;

/**
 * @ORM\Entity
 */
class UserEntity implements Entities\IIdentifiedEntity, Entities\IEntity
{
	use Entities\TIdentifiedEntity;

	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 * @IPubDoctrine\Crud(is={"required"})
	 */
	private $username;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=TRUE)
	 */
	private $notWritable;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->created;
	}

	/**
	 * @param \DateTime $created
	 */
	public function setCreatedAt(\DateTime $created)
	{
		$this->created = $created;
	}

	/**
	 * @param $text
	 */
	public function setNotWritable($text)
	{
		$this->notWritable = $text;
	}

	/**
	 * @return string
	 */
	public function getNotWritable()
	{
		return $this->notWritable;
	}
}
