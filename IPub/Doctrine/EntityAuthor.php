<?php
/**
 * EntityAuthor.php
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

use \Gedmo\Mapping\Annotation as Gedmo;

use \Doctrine\ORM\Mapping as ORM;

use \Nette\DateTime;

use \IPub\AccountModule\Entities\Users\User;

trait EntityAuthor
{
	/**
	 * @var datetime $created
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", name="created", nullable=FALSE)
	 */
	protected $created;

	/**
	 * @var string $createdBy
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="IPub\AccountModule\Entities\Users\User")
	 * @ORM\JoinColumn(name="created_by", referencedColumnName="user_id", onDelete="SET NULL")
	 */
	protected $createdBy;

	public function setCreated(DateTime $created)
	{
		$this->created = $created;

		return $this;
	}

	public function getCreated()
	{
		return $this->created;
	}

	public function setCreatedBy(User $createdBy)
	{
		$this->createdBy = $createdBy;

		return $this;
	}

	public function getCreatedBy()
	{
		return $this->createdBy;
	}
}