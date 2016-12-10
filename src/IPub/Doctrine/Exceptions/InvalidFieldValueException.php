<?php
/**
 * InvalidFieldValueException.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           10.12.16
 */

declare(strict_types = 1);

namespace IPub\Doctrine\Exceptions;

class InvalidFieldValueException extends \InvalidArgumentException implements IException
{
}
