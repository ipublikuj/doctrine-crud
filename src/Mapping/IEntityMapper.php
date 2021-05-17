<?php declare(strict_types = 1);

/**
 * IEntityMapper.php
 *
 * @copyright      More in LICENSE.md
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
use Nette\Utils;

/**
 * Doctrine CRUD entity mapper interface
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Mapping
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityMapper
{

	/**
	 * Annotation strings
	 */
	public const ANNOTATION_REQUIRED = 'required';
	public const ANNOTATION_WRITABLE = 'writable';

	/**
	 * @param Utils\ArrayHash $values
	 * @param Entities\IEntity $entity
	 * @param bool $isNew
	 *
	 * @return Entities\IEntity
	 */
	public function fillEntity(Utils\ArrayHash $values, Entities\IEntity $entity, bool $isNew = false): Entities\IEntity;

}
