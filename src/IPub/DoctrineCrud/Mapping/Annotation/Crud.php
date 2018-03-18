<?php
/**
 * Crud.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Annotation
 * @since          1.0.0
 *
 * @date           13.01.16
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD annotation for Doctrine2
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Annotation
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Crud extends Annotation
{
	/**
	 * @var string|array
	 */
	public $is;

	/**
	 * @var string|array
	 */
	public $validator;

	/**
	 * @return bool
	 */
	public function isRequired() : bool
	{
		$is = is_array($this->is) ? $this->is : [$this->is];

		return in_array(Mapping\IEntityMapper::ANNOTATION_REQUIRED, $is);
	}

	/**
	 * @return bool
	 */
	public function isWritable() : bool
	{
		$is = is_array($this->is) ? $this->is : [$this->is];

		return in_array(Mapping\IEntityMapper::ANNOTATION_WRITABLE, $is);
	}
}
