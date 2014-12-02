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

interface IEntityHydrator
{
	/**
	 * @param $values
	 * @param Doctrine\IEntity $entity
	 *
	 * @return Doctrine\IEntity
	 */
	public function hydrate($values, Doctrine\IEntity $entity);

	/**
	 * @param Doctrine\IEntity $entity
	 *
	 * @return array
	 */
	public function extract(Doctrine\IEntity &$entity);

	/**
	 * @param Doctrine\IEntity $entity
	 *
	 * @return array
	 */
	public function simpleExtract(Doctrine\IEntity &$entity);
}