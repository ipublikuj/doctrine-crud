<?php
/**
 * IEntityCrudFactory.php
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

namespace IPub\Doctrine\Crud\Create;

use IPub\Doctrine\Crud\CrudManager,
	IPub\Doctrine\EntityDao,
	IPub\Doctrine\Mapping\IEntityMapper,
	IPub\Doctrine\Entity;

class EntityCreator extends CrudManager implements IEntityCreator
{
	/**
	 * @var array
	 */
	public $beforeCreate = array();

	/**
	 * @var array
	 */
	public $afterCreate = array();

	/**
	 * @var \IPub\Doctrine\Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @var \IPub\Doctrine\EntityDao
	 */
	private $dao;

	/**
	 * @param EntityDao $dao
	 * @param IEntityMapper $entityMapper
	 */
	function __construct(EntityDao $dao, IEntityMapper $entityMapper)
	{
		$this->dao = $dao;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * @param $values
	 *
	 * @return Entity
	 */
	public function create($values)
	{
		$entity = $this->dao->createEntity();

		$this->processHooks($this->beforeCreate, array($entity, $values));
		$this->entityMapper->initValues($values, $entity);
		$this->dao->add($entity);
		$this->processHooks($this->afterCreate, array($entity, $values));

		if ($this->getFlush() === TRUE) {
			$this->dao->save();
		}

		return $entity;
	}
}