<?php
/**
 * EntityCrud.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine\Crud;

use Nette;

use Doctrine\ORM;
use Doctrine\Common;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class EntityCrud extends Nette\Object implements IEntityCrud
{
	/**
	 * Define class name
	 */
	const CLASS_NAME = __CLASS__;

	/**
	 * @var Mapping\IEntityMapper
	 */
	private $mapper;

	/**
	 * @var Crud\Delete\EntityDeleter
	 */
	private $deleter;

	/**
	 * @var Crud\Update\EntityUpdater
	 */
	private $updater;

	/**
	 * @var Crud\Create\EntityCreator
	 */
	private $creator;

	/**
	 * @var Common\Persistence\ObjectRepository
	 */
	private $repository;

	/**
	 * @var Common\Persistence\ManagerRegistry
	 */
	private $managerRegistry;

	/**
	 * @param Common\Persistence\ObjectRepository $repository
	 * @param Common\Persistence\ManagerRegistry $managerRegistry
	 * @param Mapping\IEntityMapper $mapper
	 */
	public function __construct(Common\Persistence\ObjectRepository $repository, Common\Persistence\ManagerRegistry $managerRegistry, Mapping\IEntityMapper $mapper)
	{
		$this->repository = $repository;
		$this->managerRegistry = $managerRegistry;
		$this->mapper = $mapper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityCreator()
	{
		if ($this->creator === NULL) {
			$this->creator = new Crud\Create\EntityCreator($this->repository, $this->managerRegistry, $this->mapper);
		}

		return $this->creator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityUpdater()
	{
		if ($this->updater === NULL) {
			$this->updater = new Crud\Update\EntityUpdater($this->repository, $this->managerRegistry, $this->mapper);
		}

		return $this->updater;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityDeleter()
	{
		if ($this->deleter === NULL) {
			$this->deleter = new Crud\Delete\EntityDeleter($this->repository, $this->managerRegistry);
		}

		return $this->deleter;
	}
}
