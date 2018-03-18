<?php
/**
 * EntityCrud.php
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
 * Doctrine CRUD
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityCrud implements IEntityCrud
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var string
	 */
	private $entityName;

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
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 * @param Create\IEntityCreator $entityCreatorFactory
	 * @param Update\IEntityUpdater $entityUpdaterFactory
	 * @param Delete\IEntityDeleter $entityDeleterFactory
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
	 * {@inheritdoc}
	 */
	public function getEntityCreator() : Crud\Create\EntityCreator
	{
		return $this->entityCreatorFactory->create($this->entityName, $this->entityMapper);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityUpdater() : Crud\Update\EntityUpdater
	{
		return $this->entityUpdaterFactory->create($this->entityName, $this->entityMapper);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityDeleter() : Crud\Delete\EntityDeleter
	{
		return $this->entityDeleterFactory->create($this->entityName);
	}
}
