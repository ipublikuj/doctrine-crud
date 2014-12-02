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

use Nette;
use Nette\Utils;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Mapping;

class EntityUpdater extends Crud\CrudManager implements IEntityUpdater
{
	/**
	 * @var array
	 */
	public $beforeUpdate = [];

	/**
	 * @var array
	 */
	public $afterUpdate = [];

	/**
	 * @var Mapping\IEntityMapper
	 */
	private $entityMapper;

	/**
	 * @var Doctrine\EntityDao
	 */
	private $dao;

	/**
	 * @param Doctrine\EntityDao $dao
	 * @param Mapping\IEntityMapper $entityMapper
	 */
	function __construct(Doctrine\EntityDao $dao, Mapping\IEntityMapper $entityMapper)
	{
		$this->dao = $dao;
		$this->entityMapper = $entityMapper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function update(Utils\ArrayHash $values, $entity)
	{
		if (!$entity instanceof Doctrine\IEntity) {
			$entity = $this->dao->find((int) $entity);
		}

		if (!$entity) {
			throw new Nette\InvalidArgumentException('Entity not found.');
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