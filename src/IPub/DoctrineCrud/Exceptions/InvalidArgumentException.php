<?php
/**
 * InvalidArgumentException.php
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

class InvalidArgumentException extends \InvalidArgumentException implements IException
{
}
