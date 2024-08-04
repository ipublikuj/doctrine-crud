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
 * @template T of Entities\IEntity
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityUpdater
{

	/**
	 * @param class-string<T> $entityName
	 *
	 * @return EntityUpdater<T>
	 */
	public function create(string $entityName, Mapping\IEntityMapper $entityMapper): EntityUpdater;

}
