<?php
/**
 * EntityCrudFactory.php
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

namespace IPub\Doctrine\Crud;

use Nette;

use Doctrine\Common;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD factory
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class EntityCrudFactory extends Nette\Object implements IEntityCrudFactory
{
	/**
	 * Define class name
	 */
	const CLASS_NAME = __CLASS__;

	/**
	 * @var Common\Persistence\ManagerRegistry
	 */
	private $managerRegistry;

	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @param Common\Persistence\ManagerRegistry $managerRegistry
	 * @param Mapping\IEntityMapper $entityMapper
	 */
	public function __construct(Common\Persistence\ManagerRegistry $managerRegistry, Mapping\IEntityMapper $entityMapper)
	{
		$this->managerRegistry = $managerRegistry;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function createEntityCrud($entityName)
	{
		return new EntityCrud($this->managerRegistry->getManagerForClass($entityName)->getRepository($entityName), $this->managerRegistry, $this->entityMapper);
	}
}
