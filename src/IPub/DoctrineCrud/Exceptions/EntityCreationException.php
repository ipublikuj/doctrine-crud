<?php
/**
 * EntityCreationException.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           10.12.16
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Exceptions;

use Nette;

class EntityCreationException extends Nette\InvalidStateException implements IException
{
}
