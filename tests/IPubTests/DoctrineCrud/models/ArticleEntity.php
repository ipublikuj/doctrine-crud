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
 * @date           21.01.16
 */

namespace IPubTests\Doctrine\Models;

use Doctrine\ORM\Mapping as ORM;

use IPub;
use IPub\DoctrineCrud\Entities;

use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;

/**
 * @ORM\Entity
 */
class ArticleEntity implements Entities\IEntity
{
	use Entities\TEntity;

	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $identifier;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 */
	private $title;

	/**
	 * @ORM\ManyToOne(targetEntity="UserEntity")
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 */
	private $owner;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->identifier;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return UserEntity|NULL
	 */
	public function getOwner()
	{
		return $this->owner;
	}

	/**
	 * @param UserEntity $user
	 */
	public function setOwner(UserEntity $user)
	{
		$this->owner = $user;
	}
}
