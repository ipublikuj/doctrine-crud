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

declare(strict_types = 1);

namespace IPub\Doctrine\Crud\Delete;

/**
 * Doctrine CRUD entity deleter factory
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityDeleter
{
	/**
	 * @param string $entityName
	 *
	 * @return EntityDeleter
	 */
	function create(string $entityName) : EntityDeleter;
}
