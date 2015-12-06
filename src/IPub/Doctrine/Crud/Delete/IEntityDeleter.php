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

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;

interface IEntityDeleter
{
	/**
	 * @param bool $flush
	 *
	 * @return $this
	 */
	public function setFlush($flush);

	/**
	 * @param Entities\IEntity|mixed $entity
	 *
	 * @return bool
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function delete($entity);
}