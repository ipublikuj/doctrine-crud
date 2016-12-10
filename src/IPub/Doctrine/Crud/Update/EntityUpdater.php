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

declare(strict_types = 1);

namespace IPub\Doctrine\Crud\Update;

use Doctrine\Common\Persistence;

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
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method beforeAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 * @method afterAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 */
class EntityUpdater extends Crud\CrudManager
{
	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Persistence\ManagerRegistry $managerRegistry
	 */
	public function __construct(
		string $entityName,
		Mapping\IEntityMapper $entityMapper,
		Persistence\ManagerRegistry $managerRegistry
	) {
		parent::__construct($entityName, $managerRegistry);

		$this->entityMapper = $entityMapper;
	}

	/**
	 * @param Utils\ArrayHash $values
	 * @param Entities\IEntity|int|string $entity
	 *
	 * @return Entities\IEntity
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function update(Utils\ArrayHash $values, $entity)
	{
		if (!$entity instanceof Entities\IEntity) {
			$entity = $this->entityRepository->find($entity);
		}

		if (!$entity) {
			throw new Exceptions\InvalidArgumentException('Entity not found.');
		}

		$this->processHooks($this->beforeAction, [$entity, $values]);

		$this->entityMapper->fillEntity($values, $entity, FALSE);

		$this->entityManager->persist($entity);

		$this->processHooks($this->afterAction, [$entity, $values]);

		if ($this->getFlush() === TRUE) {
			$this->entityManager->flush();
		}

		return $entity;
	}
}
