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

use Nette;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Mapping;

class EntityCrud extends Nette\Object implements IEntityCrud
{
	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @var Doctrine\EntityDao
	 */
	private $reader;

	/**
	 * @var Crud\Delete\EntityDeleter
	 */
	private $deleter;

	/**
	 * @var Crud\Update\EntityUpdater
	 */
	private $updater;

	/**
	 * @var  Crud\Create\EntityCreator
	 */
	private $creator;

	/**
	 * @param Doctrine\EntityDao $dao
	 * @param Mapping\IEntityMapper $entityMapper
	 */
	function __construct(Doctrine\EntityDao $dao, Mapping\IEntityMapper $entityMapper)
	{
		$this->reader = $dao;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityCreator()
	{
		if ($this->creator === NULL) {
			$this->creator = new Crud\Create\EntityCreator($this->getEntityReader(), $this->entityMapper);
		}

		return $this->creator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityUpdater()
	{
		if ($this->updater === NULL) {
			$this->updater = new Crud\Update\EntityUpdater($this->getEntityReader(), $this->entityMapper);
		}

		return $this->updater;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityDeleter()
	{
		if ($this->deleter === NULL) {
			$this->deleter = new Crud\Delete\EntityDeleter($this->getEntityReader());
		}

		return $this->deleter;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityReader()
	{
		return $this->reader;
	}
}