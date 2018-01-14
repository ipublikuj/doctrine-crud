<?php
/**
 * IEntityHydrator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\Mapping;

use IPub;
use IPub\DoctrineCrud;
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
	 *
	 * @return array
	 */
	public function extract(Entities\IEntity $entity, $maxLevel = 1) : array;

	/**
	 * @param Entities\IEntity $entity
	 *
	 * @return array
	 */
	public function simpleExtract(Entities\IEntity $entity) : array;
}
