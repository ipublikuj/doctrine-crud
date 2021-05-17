<?php declare(strict_types = 1);

namespace Tests\Cases\Models;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;

/**
 * @ORM\Entity
 */
class UserEntity implements Entities\IEntity
{

	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected int $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 * @IPubDoctrine\Crud(is={"required"})
	 */
	private string $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 */
	private string $name;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", nullable=TRUE)
	 */
	private ?string $notWritable;

	/**
	 * @var DateTimeInterface|null
	 *
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	private ?DateTimeInterface $createdAt;

	/**
	 * @var DateTimeInterface|null
	 *
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	private ?DateTimeInterface $updatedAt;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="ArticleEntity", mappedBy="type")
	 */
	private Collection $articles;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 *
	 * @return void
	 */
	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return DateTimeInterface|null
	 */
	public function getCreatedAt(): ?DateTimeInterface
	{
		return $this->createdAt;
	}

	/**
	 * @param DateTimeInterface $createdAt
	 *
	 * @return void
	 */
	public function setCreatedAt(DateTimeInterface $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	/**
	 * @return DateTimeInterface|null
	 */
	public function getUpdatedAt(): ?DateTimeInterface
	{
		return $this->updatedAt;
	}

	/**
	 * @param DateTimeInterface $updatedAt
	 *
	 * @return void
	 */
	public function setUpdatedAt(DateTimeInterface $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

	/**
	 * @param string $text
	 *
	 * @return void
	 */
	public function setNotWritable(string $text): void
	{
		$this->notWritable = $text;
	}

	/**
	 * @return string|null
	 */
	public function getNotWritable(): ?string
	{
		return $this->notWritable;
	}

}
