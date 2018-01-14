<?php
/**
 * IEntityMapper.php
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

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Mapping;

use Nette;
use Nette\Utils;

use IPub;
use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Entities;

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
	const ANNOTATION_REQUIRED = 'required';
	const ANNOTATION_WRITABLE = 'writable';
	const ANNOTATION_VALIDATOR = 'validator';

	/**
	 * @param Utils\ArrayHash $values
	 * @param Entities\IEntity $entity
	 * @param bool $isNew
	 *
	 * @return Entities\IEntity
	 */
	public function fillEntity(Utils\ArrayHash $values, Entities\IEntity $entity, bool $isNew = FALSE) : Entities\IEntity;
}
