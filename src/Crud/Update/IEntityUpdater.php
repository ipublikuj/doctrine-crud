<?php declare(strict_types = 1);

/**
 * IEntityUpdater.php
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

namespace IPub\DoctrineCrud\Crud\Update;

use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD entity updater factory
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @phpstan-template TEntityClass of Entities\IEntity
 */
interface IEntityUpdater
{

	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 *
	 * @return EntityUpdater
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName
	 *
	 * @phpstan-return EntityUpdater<TEntityClass>
	 */
	public function create(string $entityName, Mapping\IEntityMapper $entityMapper): EntityUpdater;

}
