<?php
/**
 * IEntityMapper.php
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

use IPub\Doctrine;

interface IEntityMapper
{
	/**
	 * @param $values
	 * @param Doctrine\IEntity $entity
	 *
	 * @return Doctrine\IEntity
	 */
	public function setValues($values, Doctrine\IEntity $entity);

	/**
	 * @param Doctrine\IEntity $entity
	 *
	 * @return array
	 */
	public function getValues(Doctrine\IEntity &$entity);

	/**
	 * @param $values
	 * @param Doctrine\IEntity $entity
	 *
	 * @return Doctrine\IEntity
	 */
	public function initValues($values, Doctrine\IEntity $entity);

	/**
	 * @param $values
	 * @param Doctrine\IEntity $entity
	 *
	 * @return Doctrine\IEntity
	 */
	public function updateValues($values, Doctrine\IEntity $entity);
}