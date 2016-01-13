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

use Doctrine\ORM;

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
class EntityCrudFactory extends Nette\Object implements IEntityCrudFactory
{
	/**
	 * Define class name
	 */
	const CLASS_NAME = __CLASS__;

	/**
	 * @var ORM\EntityManager
	 */
	private $entityManager;

	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @param ORM\EntityManager $entityManager
	 * @param Mapping\IEntityMapper $entityMapper
	 */
	function __construct(ORM\EntityManager $entityManager, Mapping\IEntityMapper $entityMapper)
	{
		$this->entityManager = $entityManager;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function createEntityCrud($entityName)
	{
		return new EntityCrud($this->entityManager->getRepository($entityName), $this->entityManager, $this->entityMapper);
	}
}
