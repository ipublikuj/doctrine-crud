<?php declare(strict_types = 1);

/**
 * EntityCrudFactory.php
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
use IPub\DoctrineCrud\Mapping;
use Nette;

/**
 * Doctrine CRUD factory
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @phpstan-template TEntityClass of \IPub\DoctrineCrud\Entities\IEntity
 */
final class EntityCrudFactory
{

	use Nette\SmartObject;

	/** @var Mapping\IEntityMapper */
	private Mapping\IEntityMapper $entityMapper;

	/** @var Create\IEntityCreator<TEntityClass> */
	private Create\IEntityCreator $entityCreatorFactory;

	/** @var Update\IEntityUpdater<TEntityClass> */
	private Update\IEntityUpdater $entityUpdaterFactory;

	/** @var Delete\IEntityDeleter<TEntityClass> */
	private Delete\IEntityDeleter $entityDeleterFactory;

	/**
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Create\IEntityCreator<TEntityClass> $entityCreatorFactory
	 * @param Update\IEntityUpdater<TEntityClass> $entityUpdaterFactory
	 * @param Delete\IEntityDeleter<TEntityClass> $entityDeleterFactory
	 */
	public function __construct(
		Mapping\IEntityMapper $entityMapper,
		Crud\Create\IEntityCreator $entityCreatorFactory,
		Crud\Update\IEntityUpdater $entityUpdaterFactory,
		Crud\Delete\IEntityDeleter $entityDeleterFactory
	) {
		$this->entityMapper = $entityMapper;

		// CRUD factories
		$this->entityCreatorFactory = $entityCreatorFactory;
		$this->entityUpdaterFactory = $entityUpdaterFactory;
		$this->entityDeleterFactory = $entityDeleterFactory;
	}

	/**
	 * @param string $entityName
	 *
	 * @return EntityCrud
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName
	 *
	 * @phpstan-return EntityCrud<TEntityClass>
	 */
	public function create(string $entityName): EntityCrud
	{
		return new EntityCrud(
			$entityName,
			$this->entityMapper,
			$this->entityCreatorFactory,
			$this->entityUpdaterFactory,
			$this->entityDeleterFactory
		);
	}

}
