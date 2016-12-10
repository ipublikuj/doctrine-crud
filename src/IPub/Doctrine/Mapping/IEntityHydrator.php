<?php
/**
 * IEntityHydrator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Mapping
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine\Mapping;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;

/**
 * Doctrine CRUD entity hydrator interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityHydrator
{
	/**
	 * @param $values
	 * @param Entities\IEntity $entity
	 *
	 * @return Entities\IEntity
	 */
	function hydrate($values, Entities\IEntity $entity);

	/**
	 * @param Entities\IEntity $entity
	 * @param int $maxLevel
	 *
	 * @return array
	 */
	function extract(Entities\IEntity $entity, $maxLevel = 1);

	/**
	 * @param Entities\IEntity $entity
	 *
	 * @return array
	 */
	function simpleExtract(Entities\IEntity $entity);
}
