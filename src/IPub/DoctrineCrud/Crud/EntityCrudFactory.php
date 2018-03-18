<?php
/**
 * EntityCrudFactory.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Crud;

use Nette;

use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD factory
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityCrudFactory
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @var Create\IEntityCreator
	 */
	private $entityCreatorFactory;

	/**
	 * @var Update\IEntityUpdater
	 */
	private $entityUpdaterFactory;

	/**
	 * @var Delete\IEntityDeleter
	 */
	private $entityDeleterFactory;

	/**
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Create\IEntityCreator $entityCreatorFactory
	 * @param Update\IEntityUpdater $entityUpdaterFactory
	 * @param Delete\IEntityDeleter $entityDeleterFactory
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
	 * @return IEntityCrud
	 */
	public function create(string $entityName) : IEntityCrud
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
