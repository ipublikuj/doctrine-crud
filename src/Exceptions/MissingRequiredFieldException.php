<?php declare(strict_types = 1);

/**
 * MissingRequiredFieldException.php
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

class MissingRequiredFieldException extends InvalidStateException
{

	/** @var Entities\IEntity */
	private Entities\IEntity $entity;

	/** @var string */
	private string $field;

	/**
	 * @param Entities\IEntity $entity
	 * @param string $field
	 * @param string $message
	 * @param int $code
	 * @param Throwable|null $previous
	 */
	public function __construct(
		Entities\IEntity $entity,
		string $field,
		string $message = '',
		int $code = 0,
		?Throwable $previous = null
	) {
		parent::__construct($message, $code, $previous);

		$this->entity = $entity;
		$this->field = $field;
	}

	/**
	 * @return Entities\IEntity
	 */
	public function getEntity(): Entities\IEntity
	{
		return $this->entity;
	}

	/**
	 * @return string
	 */
	public function getField(): string
	{
		return $this->field;
	}

}
