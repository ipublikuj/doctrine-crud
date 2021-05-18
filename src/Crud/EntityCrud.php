<?php declare(strict_types = 1);

/**
 * EntityCrud.php
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

namespace IPub\DoctrineCrud\Crud;

use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Mapping;
use Nette;

/**
 * Doctrine CRUD
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @phpstan-template    TEntityClass of Entities\IEntity
 * @phpstan-implements  IEntityCrud<TEntityClass>
 */
final class EntityCrud implements IEntityCrud
{

	use Nette\SmartObject;

	/**
	 * @var string
	 *
	 * @phpstan-var class-string<TEntityClass>
	 */
	private string $entityName;

	/** @var Mapping\IEntityMapper */
	private Mapping\IEntityMapper $entityMapper;

	/**
	 * @var Create\IEntityCreator
	 *
	 * @phpstan-var Create\IEntityCreator<TEntityClass>
	 */
	private Crud\Create\IEntityCreator $entityCreatorFactory;

	/**
	 * @var Update\IEntityUpdater
	 *
	 * @phpstan-var Update\IEntityUpdater<TEntityClass>
	 */
	private Crud\Update\IEntityUpdater $entityUpdaterFactory;

	/**
	 * @var Delete\IEntityDeleter
	 *
	 * @phpstan-var Delete\IEntityDeleter<TEntityClass>
	 */
	private Crud\Delete\IEntityDeleter $entityDeleterFactory;

	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Crud\Create\IEntityCreator $entityCreatorFactory
	 * @param Crud\Update\IEntityUpdater $entityUpdaterFactory
	 * @param Crud\Delete\IEntityDeleter $entityDeleterFactory
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName
	 * @phpstan-param Crud\Create\IEntityCreator<TEntityClass> $entityCreatorFactory
	 * @phpstan-param Crud\Update\IEntityUpdater<TEntityClass> $entityUpdaterFactory
	 * @phpstan-param Crud\Delete\IEntityDeleter<TEntityClass> $entityDeleterFactory
	 */
	public function __construct(
		string $entityName,
		Mapping\IEntityMapper $entityMapper,
		Crud\Create\IEntityCreator $entityCreatorFactory,
		Crud\Update\IEntityUpdater $entityUpdaterFactory,
		Crud\Delete\IEntityDeleter $entityDeleterFactory
	) {
		$this->entityName = $entityName;

		$this->entityMapper = $entityMapper;

		// CRUD factories
		$this->entityCreatorFactory = $entityCreatorFactory;
		$this->entityUpdaterFactory = $entityUpdaterFactory;
		$this->entityDeleterFactory = $entityDeleterFactory;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityCreator(): Crud\Create\EntityCreator
	{
		return $this->entityCreatorFactory->create($this->entityName, $this->entityMapper);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityUpdater(): Crud\Update\EntityUpdater
	{
		return $this->entityUpdaterFactory->create($this->entityName, $this->entityMapper);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityDeleter(): Crud\Delete\EntityDeleter
	{
		return $this->entityDeleterFactory->create($this->entityName);
	}

}
