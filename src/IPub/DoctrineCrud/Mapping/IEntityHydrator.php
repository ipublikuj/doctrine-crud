<?php
/**
 * IEntityHydrator.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\Mapping;

use IPub\DoctrineCrud\Entities;

/**
 * Doctrine CRUD entity hydrator interface
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityHydrator
{
	/**
	 * @param mixed $values
	 * @param Entities\IEntity $entity
	 *
	 * @return Entities\IEntity
	 */
	public function hydrate($values, Entities\IEntity $entity) : Entities\IEntity;

	/**
	 * @param Entities\IEntity $entity
	 * @param int $maxLevel
	 * @param int $level
	 *
	 * @return array
	 */
	public function extract(Entities\IEntity $entity, int $maxLevel = 1, int $level = 1) : array;

	/**
	 * @param Entities\IEntity $entity
	 *
	 * @return array
	 */
	public function simpleExtract(Entities\IEntity $entity) : array;
}
