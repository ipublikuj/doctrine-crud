<?php
/**
 * EntityDeleter.php
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

namespace IPub\Doctrine\Crud\Delete;

use Doctrine\Common;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;

/**
 * Doctrine CRUD entity deleter
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class EntityDeleter extends Crud\CrudManager implements IEntityDeleter
{
	/**
	 * @var array
	 */
	public $beforeDelete = [];

	/**
	 * @var array
	 */
	public $afterDelete = [];

	/**
	 * @var Common\Persistence\ObjectRepository
	 */
	protected $entityRepository;

	/**
	 * @var Common\Persistence\ManagerRegistry
	 */
	protected $managerRegistry;

	/**
	 * @param Common\Persistence\ObjectRepository $entityRepository
	 * @param Common\Persistence\ManagerRegistry $managerRegistry
	 */
	public function __construct(
		Common\Persistence\ObjectRepository $entityRepository,
		Common\Persistence\ManagerRegistry $managerRegistry
	) {
		$this->entityRepository = $entityRepository;
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($entity)
	{
		if (!$entity instanceof Entities\IEntity) {
			$entity = $this->entityRepository->find($entity);
		}

		if (!$entity) {
			throw new Exceptions\InvalidArgumentException('Entity not found.');
		}

		$this->processHooks($this->beforeDelete, [$entity]);

		$entityManager = $this->managerRegistry->getManagerForClass(get_class($entity));

		$entityManager->remove($entity);

		$this->processHooks($this->afterDelete);

		if ($this->getFlush() === TRUE) {
			$entityManager->flush();
		}

		return TRUE;
	}
}
