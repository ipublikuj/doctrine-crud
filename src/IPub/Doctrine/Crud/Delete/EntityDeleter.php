<?php
/**
 * EntityDeleter.php
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

namespace IPub\Doctrine\Crud\Delete;

use IPub\Doctrine\Crud\CrudManager,
	IPub\Doctrine\Entity,
	IPub\Doctrine\EntityDao;

use Nette\InvalidArgumentException,
	Nette\InvalidStateException;

class EntityDeleter extends CrudManager implements IEntityDeleter
{
	/**
	 * @var array
	 */
	public $beforeDelete = array();

	/**
	 * @var array
	 */
	public $afterDelete = array();

	/**
	 * @var  EntityDao
	 */
	private $dao;

	/**
	 * @param EntityDao $dao
	 */
	function __construct(EntityDao $dao)
	{
		$this->dao = $dao;
	}

	/**
	 * @param Entity|int $entity
	 *
	 * @return bool
	 *
	 * @throws \Nette\InvalidStateException
	 * @throws \Nette\InvalidArgumentException
	 */
	public function delete($entity)
	{
		if (!$entity instanceof Entity) {
			$entity = $this->dao->find((int) $entity);
		}

		if (!$entity) {
			throw new InvalidArgumentException('Entity not found.');
		}

		try {
			$this->processHooks($this->beforeDelete, array($entity));
			$this->dao->delete($entity);
			$this->processHooks($this->afterDelete);

			if ($this->getFlush() === TRUE) {
				$this->dao->save();
			}

			return TRUE;

		}catch (\Exception $ex) {
			throw new InvalidStateException($ex->getMessage());
		}
	}
}