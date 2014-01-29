<?php
/**
 * EntityModel.php
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

use Kdyby\Doctrine\QueryObject;

abstract class EntityModel extends \Nette\Object
{
	/**
	 * @var \Kdyby\Doctrine\EntityDao
	 */
	protected $dao;

	public function __construct(\Kdyby\Doctrine\EntityDao $dao)
	{
		$this->dao = $dao;
	}

	public function clear()
	{
		$this->dao->clear();
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getOne($id)
	{
		return $this->dao->findOneById($id);
	}

	public function getWithQuery(QueryObject $query)
	{
		return $this->dao->fetch($query);
	}

	/**
	 * @param \IPub\Doctrine\Entity $reference
	 *
	 * @return mixed
	 */
	public function add(Entity $reference)
	{
		return $this->dao->add($reference);
	}

	/**
	 * @param \IPub\Doctrine\Entity $reference
	 *
	 * @return mixed
	 */
	public function save(Entity $reference)
	{
		return $this->dao->save($reference);
	}

	/**
	 * @param \IPub\Doctrine\Entity $reference
	 *
	 * @return mixed
	 */
	public function saveAll(Entity $reference)
	{
		$ret = $this->save($reference);

		$this->dao->getEntityManager()->flush();

		return $ret;
	}

	/**
	 * @param bool $flush
	 */
	public function flush($flush = \Kdyby\Persistence\ObjectDao::FLUSH)
	{
		$this->dao->flush($flush);
	}

	/**
	 * @param \IPub\Doctrine\Entity $reference
	 * @param bool $flush
	 *
	 * @return mixed
	 */
	public function delete(Entity $reference, $flush = TRUE)
	{
		if ( $flush === TRUE ) {
			return $this->dao->delete($reference, \Kdyby\Persistence\ObjectDao::FLUSH);
		} else {
			return $this->dao->delete($reference, \Kdyby\Persistence\ObjectDao::NO_FLUSH);
		}
	}

	public function refresh(Entity $reference)
	{
		$this->dao->getEntityManager()->refresh($reference);
	}

	public function getEntityManager()
	{
		return $this->dao->getEntityManager();
	}

	/**
	 * Reindex result by key
	 *
	 * @param string
	 * @param array
	 *
	 * @return array
	 */
	protected function reindexByKey($key, array $res)
	{
		$data = array();

		foreach ($res as $row) {
			$data[callback($row, 'get'.ucfirst($key))->invoke()] = $row;
		}

		return $data;
	}
}
