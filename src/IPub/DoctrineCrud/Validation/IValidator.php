<?php
/**
 * IValidator.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Validation
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Validation;

/**
 * Doctrine CRUD validator interface
 *
 * @package        iPublikuj:DoctrineCrud!
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
