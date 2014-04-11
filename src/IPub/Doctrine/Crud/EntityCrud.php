<?php
/**
 * EntityCrud.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Crud
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Crud;

use IPub\Doctrine\Crud\Create\EntityCreator,
	IPub\Doctrine\Crud\Delete\EntityDeleter,
	IPub\Doctrine\Crud\Update\EntityUpdater,
	IPub\Doctrine\EntityDao,
	IPub\Doctrine\Mapping\IEntityMapper;

use Nette\Object;

class EntityCrud extends Object implements IEntityCrud
{
	/**
	 * @var \IPub\Doctrine\Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @var \IPub\Doctrine\EntityDao
	 */
	private $reader;

	/**
	 * @var EntityDeleter
	 */
	private $deleter;

	/**
	 * @var EntityUpdater
	 */
	private $updater;

	/**
	 * @var  EntityCreator
	 */
	private $creator;

	/**
	 * @param EntityDao $dao
	 * @param IEntityMapper $entityMapper
	 */
	function __construct(EntityDao $dao, IEntityMapper $entityMapper)
	{
		$this->reader = $dao;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * @return EntityCreator
	 */
	public function getEntityCreator()
	{
		if ($this->creator === NULL) {
			$this->creator = new EntityCreator($this->getEntityReader(), $this->entityMapper);
		}

		return $this->creator;
	}

	/**
	 * @return EntityUpdater
	 */
	public function getEntityUpdater()
	{
		if ($this->updater === NULL) {
			$this->updater = new EntityUpdater($this->getEntityReader(), $this->entityMapper);
		}

		return $this->updater;
	}

	/**
	 * @return EntityDeleter
	 */
	public function getEntityDeleter()
	{
		if ($this->deleter === NULL) {
			$this->deleter = new EntityDeleter($this->getEntityReader());
		}

		return $this->deleter;
	}

	/**
	 * @return \IPub\Doctrine\EntityDao
	 */
	public function getEntityReader()
	{
		return $this->reader;
	}
}