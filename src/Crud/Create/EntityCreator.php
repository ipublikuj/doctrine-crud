<?php declare(strict_types = 1);

/**
 * EntityCreator.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\Crud\Create;

use Doctrine\Persistence;
use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Mapping;
use Nette\Utils;
use ReflectionClass;
use ReflectionException;

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
 *
 * @phpstan-template   TEntityClass of \IPub\DoctrineCrud\Entities\IEntity
 * @phpstan-extends    Crud\CrudManager<TEntityClass>
 */
class EntityCreator extends Crud\CrudManager
{

	/**
	 * @var Mapping\IEntityMapper
	 */
	private Mapping\IEntityMapper $entityMapper;

	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Persistence\ManagerRegistry $managerRegistry
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName
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
	 * @param Entities\IEntity|null $entity
	 *
	 * @return Entities\IEntity
	 */
	public function create(Utils\ArrayHash $values, ?Entities\IEntity $entity = null): Entities\IEntity
	{
		if (!$entity instanceof Entities\IEntity) {
			try {
				// Entity name is overriden
				if ($values->offsetExists('entity') && class_exists($values->offsetGet('entity'))) {
					$entityClass = $values->offsetGet('entity');

				} else {
					$entityClass = $this->entityName;
				}

				try {
					if (class_exists($entityClass)) {
						$rc = new ReflectionClass($entityClass);

					} else {
						throw new Exceptions\InvalidStateException('Entity could not be parsed');
					}

				} catch (ReflectionException $ex) {
					throw new Exceptions\InvalidStateException('Entity could not be parsed');
				}

				if ($rc->isAbstract()) {
					throw new Exceptions\InvalidArgumentException(sprintf('Abstract entity "%s" can not be used.', $entityClass));
				}

				$constructor = $rc->getConstructor();

				if ($constructor !== null) {
					$entity = $rc->newInstanceArgs(DoctrineCrud\Helpers::autowireArguments($constructor, (array) $values));

				} else {
					$entity = $this->entityManager->getClassMetadata($this->entityName)
						->newInstance();
				}

			} catch (ReflectionException $ex) {
				// Class could not be parsed
			}
		}

		if ($entity === null || !$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgumentException('Entity could not be created.');
		}

		$this->processHooks($this->beforeAction, [$entity, $values]);

		$this->entityMapper->fillEntity($values, $entity, true);

		$this->entityManager->persist($entity);

		$this->processHooks($this->afterAction, [$entity, $values]);

		if ($this->getFlush()) {
			$this->entityManager->flush();
		}

		return $entity;
	}

}
