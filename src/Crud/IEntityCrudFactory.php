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
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @phpstan-template TEntityClass of Entities\IEntity
 */
interface IEntityCrudFactory
{

	/**
	 * @param string $entityName
	 *
	 * @return EntityCrud
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName

	 * @phpstan-return  EntityCrud<TEntityClass>
	 */
	public function create(string $entityName): EntityCrud;

}
