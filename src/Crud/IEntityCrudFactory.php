<?php declare(strict_types = 1);

/**
 * IEntityCrudFactory.php
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

namespace IPub\DoctrineCrud\Crud;

use IPub\DoctrineCrud\Entities;

/**
 * Doctrine CRUD factory
 *
 * @template T of Entities\IEntity
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityCrudFactory
{

	/**
	 * @param class-string<T> $entityName

	 * @return  EntityCrud<T>
	 */
	public function create(string $entityName): EntityCrud;

}
