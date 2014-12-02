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

use Kdyby\Doctrine\EntityManager;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Mapping;

class EntityCrudFactory extends Nette\Object implements IEntityCrudFactory
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @param EntityManager $entityManager
	 * @param Mapping\IEntityMapper $entityMapper
	 */
	function __construct(EntityManager $entityManager, Mapping\IEntityMapper $entityMapper)
	{
		$this->entityManager = $entityManager;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * @param $entityName
	 *
	 * @return EntityCrud
	 */
	public function createEntityCrud($entityName)
	{
		return new EntityCrud($this->entityManager->getDao($entityName), $this->entityMapper);
	}
}