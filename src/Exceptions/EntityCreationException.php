<?php declare(strict_types = 1);

/**
 * EntityCreationException.php
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

class EntityCreationException extends RuntimeException implements IException
{

	/** @var string */
	private string $field;

	/**
	 * @param string $field
	 * @param string $message
	 * @param int $code
	 * @param Throwable|null $previous
	 */
	public function __construct(string $field, string $message = '', int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->field = $field;
	}

	/**
	 * @return string
	 */
	public function getField(): string
	{
		return $this->field;
	}

}
