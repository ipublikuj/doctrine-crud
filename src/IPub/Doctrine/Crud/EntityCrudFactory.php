<?php
/**
 * EntityCrudFactory.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Crud
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Crud;

use Nette;

use Doctrine\ORM;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Mapping;

class EntityCrudFactory extends Nette\Object implements IEntityCrudFactory
{
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