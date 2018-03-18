<?php
/**
 * MissingRequiredFieldException.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           06.12.15
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Exceptions;

use IPub\DoctrineCrud\Entities;

class MissingRequiredFieldException extends InvalidStateException
{
	/**
	 * @var Entities\IEntity
	 */
	private $entity;

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @param Entities\IEntity $entity
	 * @param string $field
	 * @param string $message
	 * @param int $code
	 * @param \Exception|NULL $previous
	 */
	public function __construct(Entities\IEntity $entity, string $field, $message = '', $code = 0, \Exception $previous = NULL)
	{
		parent::__construct($message, $code, $previous);

		$this->entity = $entity;
		$this->field = $field;
	}

	/**
	 * @return Entities\IEntity
	 */
	public function getEntity() : Entities\IEntity
	{
		return $this->entity;
	}

	/**
	 * @return string
	 */
	public function getField() : string
	{
		return $this->field;
	}
}
