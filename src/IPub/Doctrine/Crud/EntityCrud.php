<?php
/**
 * EntityCrud.php
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

namespace IPub\Doctrine\Crud;

use Nette;

use IPub;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class EntityCrud extends Nette\Object implements IEntityCrud
{
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
