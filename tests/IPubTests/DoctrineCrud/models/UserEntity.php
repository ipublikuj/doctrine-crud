<?php
/**
 * Test: IPub\DoctrineCrud\Models
 * @testCase
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

namespace IPubTests\DoctrineCrud\Models;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 * @IPubDoctrine\Crud(is={"required"})
	 */
	private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 */
	private $name;

	/**
	 * @var string|NULL
	 *
	 * @ORM\Column(type="string", nullable=TRUE)
	 */
	private $notWritable;

	/**
	 * @var \DateTimeInterface|NULL
	 *
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	private $createdAt;

	/**
	 * @var \DateTimeInterface|NULL
	 *
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	private $updatedAt;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="ArticleEntity", mappedBy="type")
	 */
	private $articles;

	/**
	 * @return string
	 */
	public function getUsername() : string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 *
	 * @return void
	 */
	public function setUsername(string $username) : void
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	/**
	 * @return \DateTimeInterface|NULL
	 */
	public function getCreatedAt() : ?\DateTimeInterface
	{
		return $this->createdAt;
	}

	/**
	 * @param \DateTimeInterface $createdAt
	 *
	 * @return void
	 */
	public function setCreatedAt(\DateTimeInterface $createdAt) : void
	{
		$this->createdAt = $createdAt;
	}

	/**
	 * @return \DateTimeInterface|NULL
	 */
	public function getUpdatedAt() : ?\DateTimeInterface
	{
		return $this->updatedAt;
	}

	/**
	 * @param \DateTimeInterface $updatedAt
	 *
	 * @return void
	 */
	public function setUpdatedAt(\DateTimeInterface $updatedAt) : void
	{
		$this->updatedAt = $updatedAt;
	}

	/**
	 * @param $text
	 *
	 * @return void
	 */
	public function setNotWritable(string $text) : void
	{
		$this->notWritable = $text;
	}

	/**
	 * @return string|NULL
	 */
	public function getNotWritable() : ?string
	{
		return $this->notWritable;
	}
}
