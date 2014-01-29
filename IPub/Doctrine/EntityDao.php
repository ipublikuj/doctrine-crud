<?php
/**
 * EntityDao.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	common
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine;

use Nette\Reflection\ClassType;

use Doctrine\DBAL\LockMode;

class EntityDao extends \Kdyby\Doctrine\EntityDao
{
	/**
	 * @return \IPub\Doctrine\Entity
	 */
	public function createEntity()
	{
		$reflection = new ClassType($this->getEntityName());
		return $reflection->newInstanceArgs(func_get_args());
	}

	/**
	 * @param mixed $id
	 * @param int $lockMode
	 * @param NULL $lockVersion
	 *
	 * @return object
	 */
	public function find($id, $lockMode = LockMode::NONE, $lockVersion = NULL)
	{
		return parent::find((int) $id, $lockMode, $lockVersion);
	}
}