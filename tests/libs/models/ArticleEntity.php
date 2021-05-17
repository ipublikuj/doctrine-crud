<?php declare(strict_types = 1);

namespace Tests\Cases\Models;

use Doctrine\ORM\Mapping as ORM;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;

/**
 * @ORM\Entity
 */
class ArticleEntity implements Entities\IEntity
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
	 * @var string|null
	 *
	 * @ORM\Column(type="string", nullable=true)
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 */
	private ?string $title;

	/**
	 * @var UserEntity|null
	 *
	 * @ORM\ManyToOne(targetEntity="UserEntity")
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 */
	private ?UserEntity $owner;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string|null
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return void
	 */
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @return UserEntity|null
	 */
	public function getOwner(): ?UserEntity
	{
		return $this->owner;
	}

	/**
	 * @param UserEntity $user
	 *
	 * @return void
	 */
	public function setOwner(UserEntity $user): void
	{
		$this->owner = $user;
	}

}
