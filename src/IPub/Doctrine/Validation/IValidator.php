<?php
/**
 * IValidator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Validation
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\Doctrine\Validation;

/**
 * Doctrine CRUD validator interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Validation
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IValidator
{
	/**
	 * @param mixed $data
	 *
	 * @return bool
	 */
	public function validate($data) : bool;
}
