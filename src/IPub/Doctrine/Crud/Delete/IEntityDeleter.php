<?php
/**
 * IEntityDeleter.php
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

interface IEntityDeleter
{
	/**
	 * @param bool $flush
	 *
	 * @return $this
	 */
	public function setFlush($flush);

	/**
	 * @param Doctrine\IEntity|int $entity
	 *
	 * @return bool
	 *
	 * @throws Nette\InvalidStateException
	 */
	public function delete($entity);
}