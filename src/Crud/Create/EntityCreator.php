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

use Doctrine\DBAL;
use Doctrine\ORM;
use Doctrine\Persistence;
use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use IPub\DoctrineCrud\Mapping;
use Nette\Utils;
use ReflectionClass;
use ReflectionException;
use function class_exists;
use function is_string;
use function sprintf;

/**
 * Doctrine CRUD entity creator
 *
 * @template   T of Entities\IEntity
 * @extends    Crud\CrudManager<T>
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class EntityCreator extends Crud\CrudManager
{

	/** @var array<callable(Entities\IEntity, Utils\ArrayHash): void> */
	public array $beforeAction = [];

	/** @var array<callable(Entities\IEntity, Utils\ArrayHash): void> */
	public array $afterAction = [];

	/**
	 * @param class-string<T> $entityName
	 */
	public function __construct(
		string $entityName,
		private readonly Mapping\IEntityMapper $entityMapper,
		Persistence\ManagerRegistry $managerRegistry,
	)
	{
		parent::__construct($entityName, $managerRegistry);
	}

	/**
	 * @throws Exceptions\EntityCreation
	 * @throws Exceptions\InvalidArgument
	 * @throws Exceptions\InvalidState
	 */
	public function create(Utils\ArrayHash $values, Entities\IEntity|null $entity = null): Entities\IEntity
	{
		if (!$entity instanceof Entities\IEntity) {
			try {
				// Entity name is override
				$entityClass = $values->offsetExists('entity')
					&& is_string($values->offsetGet('entity'))
					&& class_exists($values->offsetGet('entity'))
						? $values->offsetGet('entity')
						: $this->entityName;

				try {
					if (class_exists($entityClass)) {
						$rc = new ReflectionClass($entityClass);

					} else {
						throw new Exceptions\InvalidState('Entity could not be parsed');
					}
				} catch (ReflectionException) {
					throw new Exceptions\InvalidState('Entity could not be parsed');
				}

				if ($rc->isAbstract()) {
					throw new Exceptions\InvalidArgument(
						sprintf('Abstract entity "%s" can not be used.', $entityClass),
					);
				}

				$constructor = $rc->getConstructor();

				$entity = $constructor !== null ? $rc->newInstanceArgs(
					DoctrineCrud\Helpers::autowireArguments($constructor, (array) $values),
				) : $this->entityManager->getClassMetadata($this->entityName)
					->newInstance();
			} catch (ReflectionException) {
				// Class could not be parsed
			}
		}

		if ($entity === null || !$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgument('Entity could not be created.');
		}

		Utils\Arrays::invoke($this->beforeAction, $entity, $values);

		$this->entityMapper->fillEntity($values, $entity, true);

		$this->entityManager->persist($entity);

		Utils\Arrays::invoke($this->afterAction, $entity, $values);

		if ($this->getFlush()) {
			try {
				$this->entityManager->flush();
			} catch (DBAL\Exception\UniqueConstraintViolationException | ORM\Exception\ORMException $ex) {
				throw new Exceptions\InvalidState('Entity could not be created', $ex->getCode(), $ex);
			}
		}

		return $entity;
	}

}
