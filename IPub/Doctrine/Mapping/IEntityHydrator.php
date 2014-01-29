<?php
/**
 * IEntityHydrator.php
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

interface IEntityHydrator
{
	/**
	 * @param $values
	 * @param BaseEntity $entity
	 *
	 * @return BaseEntity
	 */
	public function hydrate($values, BaseEntity $entity);

	/**
	 * @param BaseEntity $entity
	 *
	 * @return array
	 */
	public function extract(BaseEntity &$entity);

	/**
	 * @param BaseEntity $entity
	 *
	 * @return array
	 */
	public function simpleExtract(BaseEntity &$entity);
}