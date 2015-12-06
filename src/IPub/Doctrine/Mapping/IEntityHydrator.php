<?php
/**
 * IEntityHydrator.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Mapping
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Mapping;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;

interface IEntityHydrator
{
	/**
	 * @param $values
	 * @param Entities\IEntity $entity
	 *
	 * @return Entities\IEntity
	 */
	public function hydrate($values, Entities\IEntity $entity);

	/**
	 * @param Entities\IEntity $entity
	 *
	 * @return array
	 */
	public function extract(Entities\IEntity $entity);

	/**
	 * @param Doctrine\IEntity $entity
	 *
	 * @return array
	 */
	public function simpleExtract(Entities\IEntity $entity);
}