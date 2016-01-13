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

use Doctrine\ORM;

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
	 * @var ORM\EntityRepository
	 */
	protected $entityRepository;

	/**
	 * @var ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @param ORM\EntityRepository $entityRepository
	 * @param ORM\EntityManager $entityManager
	 */
	function __construct(
		ORM\EntityRepository $entityRepository,
		ORM\EntityManager $entityManager
	) {
		$this->entityRepository = $entityRepository;
		$this->entityManager = $entityManager;
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

		$this->entityManager->remove($entity);

		$this->processHooks($this->afterDelete);

		if ($this->getFlush() === TRUE) {
			$this->entityManager->flush();
		}

		return TRUE;
	}
}
