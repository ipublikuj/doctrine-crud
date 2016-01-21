<?php
/**
 * EntityUpdater.php
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

namespace IPub\Doctrine\Crud\Update;

use Doctrine\Common;

use Nette;
use Nette\Utils;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD entity updater
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class EntityUpdater extends Crud\CrudManager implements IEntityUpdater
{
	/**
	 * @var array
	 */
	public $beforeUpdate = [];

	/**
	 * @var array
	 */
	public $afterUpdate = [];

	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

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
	 * @param Mapping\IEntityMapper $entityMapper
	 */
	public function __construct(
		Common\Persistence\ObjectRepository $entityRepository,
		Common\Persistence\ManagerRegistry $managerRegistry,
		Mapping\IEntityMapper $entityMapper
	) {
		$this->entityMapper = $entityMapper;
		$this->entityRepository = $entityRepository;
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritdoc}
	 */
	public function update(Utils\ArrayHash $values, $entity)
	{
		if (!$entity instanceof Entities\IEntity) {
			$entity = $this->entityRepository->find($entity);
		}

		if (!$entity) {
			throw new Exceptions\InvalidArgumentException('Entity not found.');
		}

		$this->processHooks($this->beforeUpdate, [$entity, $values]);

		$this->entityMapper->fillEntity($values, $entity, FALSE);

		$entityManager = $this->managerRegistry->getManagerForClass(get_class($entity));

		$entityManager->persist($entity);

		$this->processHooks($this->afterUpdate, [$entity, $values]);

		if ($this->getFlush() === TRUE) {
			$entityManager->flush();
		}

		return $entity;
	}
}
