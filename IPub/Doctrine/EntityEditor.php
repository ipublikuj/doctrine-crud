<?php
/**
 * EntityEditor.php
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

trait EntityEditor
{
	/**
	 * @var datetime $updated
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime", name="modified", nullable=TRUE)
	 */
	protected $modified;

	/**
	 * @var string $modifiedBy
	 *
	 * @Gedmo\Blameable(on="update")
	 * @ORM\ManyToOne(targetEntity="IPub\AccountModule\Entities\Users\User")
	 * @ORM\JoinColumn(name="modified_by", referencedColumnName="user_id", onDelete="SET NULL")
	 */
	protected $modifiedBy;

	public function setModified(DateTime $modified)
	{
		$this->modified = $modified;

		return $this;
	}

	public function getModified()
	{
		return $this->modified;
	}

	public function setModifiedBy(User $modifiedBy)
	{
		$this->modifiedBy = $modifiedBy;

		return $this;
	}

	public function getModifiedBy()
	{
		return $this->modifiedBy;
	}
}