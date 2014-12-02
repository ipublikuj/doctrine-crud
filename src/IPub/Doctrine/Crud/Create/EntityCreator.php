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

use Nette;
use Nette\Utils;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Mapping;

class EntityCreator extends Crud\CrudManager implements IEntityCreator
{
	/**
	 * @var array
	 */
	public $beforeCreate = [];

	/**
	 * @var array
	 */
	public $afterCreate = [];

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
	public function create(Utils\ArrayHash $values, Doctrine\IEntity $entity = NULL)
	{
		if (!$entity instanceof Doctrine\IEntity) {
			$entity = $this->dao->createEntity();
		}

		if (!$entity) {
			throw new Nette\InvalidArgumentException('Entity could not be created.');
		}

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