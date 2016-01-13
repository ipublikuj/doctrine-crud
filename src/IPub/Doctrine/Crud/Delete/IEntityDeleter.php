<?php
/**
 * IEntityDeleter.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine\Crud\Delete;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;

/**
 * Doctrine CRUD entity deleter interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IEntityDeleter
{
	/**
	 * @param bool $flush
	 *
	 * @return $this
	 */
	function setFlush($flush);

	/**
	 * @param Entities\IEntity|mixed $entity
	 *
	 * @return bool
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	function delete($entity);
}
