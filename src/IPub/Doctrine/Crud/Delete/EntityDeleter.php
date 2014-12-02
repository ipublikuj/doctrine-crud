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

use Nette;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Crud;

class EntityDeleter extends Crud\CrudManager implements IEntityDeleter
{
	/**
	 * @var array
	 */
	public $beforeDelete = [];

	/**
	 * @var array
	 */
	public $afterDelete = [];

	/**
	 * @var Doctrine\EntityDao
	 */
	private $dao;

	/**
	 * @param Doctrine\EntityDao $dao
	 */
	function __construct(Doctrine\EntityDao $dao)
	{
		$this->dao = $dao;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($entity)
	{
		if (!$entity instanceof Doctrine\IEntity) {
			$entity = $this->dao->find((int) $entity);
		}

		if (!$entity) {
			throw new Nette\InvalidArgumentException('Entity not found.');
		}

		$this->processHooks($this->beforeDelete, array($entity));

		$this->dao->delete($entity);

		$this->processHooks($this->afterDelete);

		if ($this->getFlush() === TRUE) {
			$this->dao->save();
		}

		return TRUE;
	}
}