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
 * @template    T of Entities\IEntity
 * @implements  IEntityCrud<T>
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityCrud implements IEntityCrud
{

	use Nette\SmartObject;

	/**
	 * @param class-string<T> $entityName
	 * @param Crud\Create\IEntityCreator<T> $entityCreatorFactory
	 * @param Crud\Update\IEntityUpdater<T> $entityUpdaterFactory
	 * @param Crud\Delete\IEntityDeleter<T> $entityDeleterFactory
	 */
	public function __construct(
		private string $entityName,
		private Mapping\IEntityMapper $entityMapper,
		private Crud\Create\IEntityCreator $entityCreatorFactory,
		private Crud\Update\IEntityUpdater $entityUpdaterFactory,
		private Crud\Delete\IEntityDeleter $entityDeleterFactory,
	)
	{
		// CRUD factories
	}

	public function getEntityCreator(): Crud\Create\EntityCreator
	{
		return $this->entityCreatorFactory->create($this->entityName, $this->entityMapper);
	}

	public function getEntityUpdater(): Crud\Update\EntityUpdater
	{
		return $this->entityUpdaterFactory->create($this->entityName, $this->entityMapper);
	}

	public function getEntityDeleter(): Crud\Delete\EntityDeleter
	{
		return $this->entityDeleterFactory->create($this->entityName);
	}

}
