<?php declare(strict_types = 1);

/**
 * MissingRequiredField.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           06.12.15
 */

namespace IPub\DoctrineCrud\Exceptions;

use IPub\DoctrineCrud\Entities;
use Throwable;

class MissingRequiredField extends InvalidState
{

	public function __construct(
		private Entities\IEntity $entity,
		private string $field,
		string $message = '',
		int $code = 0,
		Throwable|null $previous = null,
	)
	{
		parent::__construct($message, $code, $previous);
	}

	public function getEntity(): Entities\IEntity
	{
		return $this->entity;
	}

	public function getField(): string
	{
		return $this->field;
	}

}
