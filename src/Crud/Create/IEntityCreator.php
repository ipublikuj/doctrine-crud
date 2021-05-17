<?php declare(strict_types = 1);

/**
 * IEntityCreator.php
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

namespace IPub\DoctrineCrud\Crud\Create;

use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD entity creator factory
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @phpstan-template TEntityClass of \IPub\DoctrineCrud\Entities\IEntity
 */
interface IEntityCreator
{

	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 *
	 * @return EntityCreator
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName
	 *
	 * @phpstan-return EntityCreator<TEntityClass>
	 */
	public function create(string $entityName, Mapping\IEntityMapper $entityMapper): EntityCreator;

}
