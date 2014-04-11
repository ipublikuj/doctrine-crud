<?php
/**
 * EntityUpdater.php
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

namespace IPub\Doctrine\Crud\Update;

use IPub\Doctrine\Crud\CrudManager,
	IPub\Doctrine\EntityDao,
	IPub\Doctrine\Mapping\IEntityMapper,
	IPub\Doctrine\Entity;

use Nette\InvalidArgumentException;

class EntityUpdater extends CrudManager implements IEntityUpdater
{
	/**
	 * @var array
	 */
	public $beforeUpdate = array();

	/**
	 * @var array
	 */
	public $afterUpdate = array();

	/**
	 * @var \IPub\Doctrine\Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @var  EntityDao
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
	 * @param Entity|int $entity
	 * @param $values
	 *
	 * @return Entity|object
	 *
	 * @throws \Nette\InvalidArgumentException
	 */
	public function update($entity, $values)
	{
		if (!$entity instanceof Entity) {
			$entity = $this->dao->find((int) $entity);
		}

		if (!$entity) {
			throw new InvalidArgumentException('Entity not found.');
		}

		$this->processHooks($this->beforeUpdate, array($entity, $values));

		$this->entityMapper->updateValues($values, $entity);
		$this->dao->add($entity);

		$this->processHooks($this->afterUpdate, array($entity, $values));

		if ($this->getFlush() === TRUE) {
			$this->dao->getEntityManager()->flush();
		}

		return $entity;
	}
}