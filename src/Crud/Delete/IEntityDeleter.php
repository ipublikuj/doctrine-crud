<?php declare(strict_types = 1);

/**
 * IEntityDeleter.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\Crud\Delete;

use IPub\DoctrineCrud\Entities;

/**
 * Doctrine CRUD entity deleter factory
 *
 * @template T of Entities\IEntity
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityDeleter
{

	/**
	 * @param class-string<T> $entityName
	 *
	 * @return EntityDeleter<T>
	 */
	public function create(string $entityName): EntityDeleter;

}
