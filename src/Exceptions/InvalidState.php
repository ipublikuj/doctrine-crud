<?php declare(strict_types = 1);

/**
 * InvalidStateException.php
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

use RuntimeException;

class InvalidState extends RuntimeException implements Exception
{

}
