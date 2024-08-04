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
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Mapping;
use Nette;

/**
 * Doctrine CRUD factory
 *
 * @template T of Entities\IEntity
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityCrudFactory
{

	use Nette\SmartObject;

	/** @var Create\IEntityCreator<T> */
	private Create\IEntityCreator $entityCreatorFactory;

	/** @var Update\IEntityUpdater<T> */
	private Update\IEntityUpdater $entityUpdaterFactory;

	/** @var Delete\IEntityDeleter<T> */
	private Delete\IEntityDeleter $entityDeleterFactory;

	/**
	 * @param Create\IEntityCreator<T> $entityCreatorFactory
	 * @param Update\IEntityUpdater<T> $entityUpdaterFactory
	 * @param Delete\IEntityDeleter<T> $entityDeleterFactory
	 */
	public function __construct(
		private Mapping\IEntityMapper $entityMapper,
		Crud\Create\IEntityCreator $entityCreatorFactory,
		Crud\Update\IEntityUpdater $entityUpdaterFactory,
		Crud\Delete\IEntityDeleter $entityDeleterFactory,
	)
	{
		// CRUD factories
		$this->entityCreatorFactory = $entityCreatorFactory;
		$this->entityUpdaterFactory = $entityUpdaterFactory;
		$this->entityDeleterFactory = $entityDeleterFactory;
	}

	/**
	 * @param class-string<T> $entityName
	 *
	 * @return EntityCrud<T>
	 */
	public function create(string $entityName): EntityCrud
	{
		return new EntityCrud(
			$entityName,
			$this->entityMapper,
			$this->entityCreatorFactory,
			$this->entityUpdaterFactory,
			$this->entityDeleterFactory,
		);
	}

}
