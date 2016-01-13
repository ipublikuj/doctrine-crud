<?php
/**
 * Crud.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Annotation
 * @since          1.0.0
 *
 * @date           13.01.16
 */

namespace IPub\Doctrine\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

use IPub\Doctrine;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD annotation for Doctrine2
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Annotation
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
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
	 * @var mixed
	 */
	public $validator;

	/**
	 * @return bool
	 */
	public function isRequired()
	{
		$is = is_array($this->is) ? $this->is : [$this->is];

		return in_array(Mapping\IEntityMapper::ANNOTATION_REQUIRED, $is);
	}

	/**
	 * @return bool
	 */
	public function isWritable()
	{
		$is = is_array($this->is) ? $this->is : [$this->is];

		return in_array(Mapping\IEntityMapper::ANNOTATION_WRITABLE, $is);
	}
}
