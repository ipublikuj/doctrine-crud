<?php
/**
 * IEntityMapper.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Mapping
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Mapping;

use Kdyby\Doctrine\Entities\BaseEntity;

interface IEntityMapper
{
	/**
	 * @param $values
	 * @param BaseEntity $entity
	 *
	 * @return BaseEntity
	 */
	public function setValues($values, BaseEntity $entity);

	/**
	 * @param BaseEntity $entity
	 *
	 * @return array
	 */
	public function getValues(BaseEntity &$entity);

	/**
	 * @param $values
	 * @param BaseEntity $entity
	 *
	 * @return BaseEntity
	 */
	public function initValues($values, BaseEntity $entity);

	/**
	 * @param $values
	 * @param BaseEntity $entity
	 *
	 * @return BaseEntity
	 */
	public function updateValues($values, BaseEntity $entity);
}