<?php
/**
 * IEntityCrudFactory.php
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

namespace IPub\Doctrine\Crud\Create;

use Doctrine\ORM;
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
 * Doctrine CRUD entity creator
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class EntityCreator extends Crud\CrudManager implements IEntityCreator
{
	/**
	 * @var array
	 */
	public $beforeCreate = [];

	/**
	 * @var array
	 */
	public $afterCreate = [];

	/**
	 * @var Mapping\IEntityMapper
	 */
	protected $entityMapper;

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
	public function create(Utils\ArrayHash $values, Entities\IEntity $entity = NULL)
	{
		if (!$entity instanceof Entities\IEntity) {
			$entityName = $this->entityRepository->getClassName();
			$entity = $this->managerRegistry->getManagerForClass($entityName)->getClassMetadata($entityName)->newInstance();
		}

		if (!$entity || !$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgumentException('Entity could not be created.');
		}

		$this->processHooks($this->beforeCreate, [$entity, $values]);

		$this->entityMapper->fillEntity($values, $entity, TRUE);

		$entityManager = $this->managerRegistry->getManagerForClass(get_class($entity));

		$entityManager->persist($entity);

		$this->processHooks($this->afterCreate, [$entity, $values]);

		if ($this->getFlush() === TRUE) {
			$entityManager->flush();
		}

		return $entity;
	}
}
