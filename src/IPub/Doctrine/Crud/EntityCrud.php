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

use Doctrine\ORM;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Mapping;

class EntityCrud extends Nette\Object implements IEntityCrud
{
	/**
	 * @var Mapping\IEntityMapper
	 */
	protected $mapper;

	/**
	 * @var Crud\Delete\EntityDeleter
	 */
	protected $deleter;

	/**
	 * @var Crud\Update\EntityUpdater
	 */
	protected $updater;

	/**
	 * @var Crud\Create\EntityCreator
	 */
	protected $creator;

	/**
	 * @var ORM\EntityRepository
	 */
	protected $repository;

	protected $em;

	/**
	 * @param ORM\EntityRepository $repository
	 * @param ORM\EntityManager $em
	 * @param Mapping\IEntityMapper $mapper
	 */
	function __construct(ORM\EntityRepository $repository, ORM\EntityManager $em, Mapping\IEntityMapper $mapper)
	{
		$this->repository = $repository;
		$this->em = $em;
		$this->mapper = $mapper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityCreator()
	{
		if ($this->creator === NULL) {
			$this->creator = new Crud\Create\EntityCreator($this->repository, $this->em, $this->mapper);
		}

		return $this->creator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityUpdater()
	{
		if ($this->updater === NULL) {
			$this->updater = new Crud\Update\EntityUpdater($this->repository, $this->em, $this->mapper);
		}

		return $this->updater;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityDeleter()
	{
		if ($this->deleter === NULL) {
			$this->deleter = new Crud\Delete\EntityDeleter($this->repository, $this->em);
		}

		return $this->deleter;
	}
}