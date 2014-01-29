<?php
/**
 * EntityCrudFactory.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Crud
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Crud;

use IPub\Doctrine\Mapping\IEntityMapper;

use Kdyby\Doctrine\EntityManager;

use Nette\Object;

class EntityCrudFactory extends Object implements IEntityCrudFactory
{
	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $entityManager;

	/**
	 * @var \IPub\Doctrine\Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @param EntityManager $entityManager
	 * @param IEntityMapper $entityMapper
	 */
	function __construct(EntityManager $entityManager, IEntityMapper $entityMapper)
	{
		$this->entityManager = $entityManager;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * @param $entityName
	 * @return EntityCrud
	 */
	public function createEntityCrud($entityName)
	{
		return new EntityCrud($this->entityManager->getDao($entityName), $this->entityMapper);
	}
}