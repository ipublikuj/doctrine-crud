<?php declare(strict_types = 1);

namespace IPub\DoctrineCrud\Tests\Fixtures\Dummy;

use Doctrine\ORM\Mapping as ORM;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Mapping\Attribute as IPubDoctrine;

#[ORM\Entity]
class ArticleEntity implements Entities\IEntity
{

	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	#[ORM\GeneratedValue(strategy: 'AUTO')]
	protected int $id;

	#[ORM\Column(type: 'string')]
	#[IPubDoctrine\Crud(required: true, writable: true)]
	private string|null $title;

	#[ORM\ManyToOne(targetEntity: UserEntity::class, inversedBy: 'articles')]
	#[IPubDoctrine\Crud(writable: true)]
	private UserEntity|null $owner;

	public function getId(): int
	{
		return $this->id;
	}

	public function getTitle(): string|null
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function getOwner(): UserEntity|null
	{
		return $this->owner;
	}

	public function setOwner(UserEntity $user): void
	{
		$this->owner = $user;
	}

}
