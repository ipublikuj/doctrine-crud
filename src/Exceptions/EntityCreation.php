<?php declare(strict_types = 1);

/**
 * EntityCreation.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           10.12.16
 */

namespace IPub\DoctrineCrud\Exceptions;

use RuntimeException;
use Throwable;

class EntityCreation extends RuntimeException implements Exception
{

	public function __construct(
		private string $field,
		string $message = '',
		int $code = 0,
		Throwable|null $previous = null,
	)
	{
		parent::__construct($message, $code, $previous);
	}

	public function getField(): string
	{
		return $this->field;
	}

}
