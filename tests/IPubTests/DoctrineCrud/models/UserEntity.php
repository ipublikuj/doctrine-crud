<?php
/**
 * Test: IPub\DoctrineCrud\Models
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

namespace IPubTests\DoctrineCrud\Models;

use Doctrine\ORM\Mapping as ORM;

use IPub;
use IPub\DoctrineCrud\Entities;

use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;

/**
 * @ORM\Entity
 */
class UserEntity implements Entities\IIdentifiedEntity
{
	use Entities\TEntity;
	use Entities\TIdentifiedEntity;

	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

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
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	private $createdAt;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	private $updatedAt;

	/**
	 * @ORM\OneToMany(targetEntity="ArticleEntity", mappedBy="type")
	 */
	private $articles;

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
		return $this->createdAt;
	}

	/**
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->createdAt = $createdAt;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * @param \DateTime $updatedAt
	 */
	public function setUpdatedAt(\DateTime $updatedAt)
	{
		$this->updatedAt = $updatedAt;
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
