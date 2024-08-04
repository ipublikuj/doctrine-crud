<?php declare(strict_types = 1);

/**
 * Crud.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Annotation
 * @since          1.0.0
 *
 * @date           06.02.24
 */

namespace IPub\DoctrineCrud\Mapping\Attribute;

use Attribute;
use Doctrine\ORM\Mapping as ORMMapping;

/**
 * Doctrine CRUD attribute for Doctrine2
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Annotation
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Crud implements ORMMapping\MappingAttribute
{

	/** @var string|array<string> */
	public string|array $is;

	public bool $required;

	public bool $writable;

	public function __construct(bool|null $required = null, bool|null $writable = null)
	{
		$this->required = $required ?? false;
		$this->writable = $writable ?? false;
	}

	public function isRequired(): bool
	{
		return $this->required;
	}

	public function isWritable(): bool
	{
		return $this->writable;
	}

}
