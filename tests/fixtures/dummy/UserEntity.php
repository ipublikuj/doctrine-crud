<?php declare(strict_types = 1);

namespace IPub\DoctrineCrud\Tests\Fixtures\Dummy;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Mapping\Attribute as IPubDoctrine;

#[ORM\Entity]
class UserEntity implements Entities\IEntity
{

	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	#[ORM\GeneratedValue(strategy: 'AUTO')]
	protected int $id;

	#[ORM\Column(type: 'string')]
	#[IPubDoctrine\Crud(required: true)]
	private string $username;

	#[ORM\Column(type: 'string')]
	#[IPubDoctrine\Crud(required: true, writable: true)]
	private string $name;

	#[ORM\Column(type: 'string', nullable: true)]
	private string|null $notWritable;

	#[ORM\Column(type: 'datetime', nullable: true)]
	private DateTimeInterface|null $createdAt;

	#[ORM\Column(type: 'datetime', nullable: true)]
	private DateTimeInterface|null $updatedAt;

	#[ORM\OneToMany(mappedBy: 'owner', targetEntity: ArticleEntity::class)]
	private Collection $articles;

	public function __construct()
	{
		$this->articles = new ArrayCollection();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getCreatedAt(): DateTimeInterface|null
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeInterface $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt(): DateTimeInterface|null
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(DateTimeInterface $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

	public function setNotWritable(string $text): void
	{
		$this->notWritable = $text;
	}

	public function getNotWritable(): string|null
	{
		return $this->notWritable;
	}

	public function getArticles(): array
	{
		return $this->articles->toArray();
	}

}
