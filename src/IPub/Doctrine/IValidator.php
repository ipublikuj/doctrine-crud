<?php
/**
 * IValidator.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine;

/**
 * Doctrine CRUD validator interface
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IValidator
{
	/**
	 * Define class name
	 */
	const INTERFACE_NAME = __CLASS__;

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	public function validate($data);
}
