<?php
/**
 * EntityCreationException.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           10.12.16
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Exceptions;

class EntityCreationException extends \RuntimeException implements IException
{

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @param string $field
	 * @param string $message
	 * @param int $code
	 * @param \Exception|NULL $previous
	 */
	public function __construct(string $field, $message = '', $code = 0, \Exception $previous = null)
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
