<?php
/**
 * EntityCreator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Crud\Create;

use Doctrine\Common\Persistence;

use Nette;
use Nette\Utils;

use IPub;
use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD entity creator
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method beforeAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 * @method afterAction(Entities\IEntity $entity, Utils\ArrayHash $values)
 */
class EntityCreator extends Crud\CrudManager
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
	 * @param Entities\IEntity|NULL $entity
	 *
	 * @return Entities\IEntity
	 * 
	 * @throws Exceptions\InvalidArgumentException
	 * @throws Exceptions\EntityCreationException
	 */
	public function create(Utils\ArrayHash $values, Entities\IEntity $entity = NULL)
	{
		if (!$entity instanceof Entities\IEntity) {
			$rc = new \ReflectionClass($this->entityName);

			if ($constructor = $rc->getConstructor()) {
				$entity = $rc->newInstanceArgs(DoctrineCrud\Helpers::autowireArguments($constructor, (array) $values));

			} else {
				$entity = $this->entityManager->getClassMetadata($this->entityName)->newInstance();
			}
		}

		if (!$entity || !$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgumentException('Entity could not be created.');
		}

		$this->processHooks($this->beforeAction, [$entity, $values]);

		$this->entityMapper->fillEntity($values, $entity, TRUE);

		$this->entityManager->persist($entity);

		$this->processHooks($this->afterAction, [$entity, $values]);

		if ($this->getFlush()) {
			$this->entityManager->flush();
		}

		return $entity;
	}
}
