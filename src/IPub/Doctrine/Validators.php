<?php
/**
 * Validators.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           06.12.15
 */

namespace IPub\Doctrine;

use Nette;

/**
 * Doctrine CRUD validators container
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class Validators extends Nette\Object
{
	/**
	 * Define class name
	 */
	const CLASS_NAME = __CLASS__;

	/**
	 * @var IValidator[]
	 */
	private $validators = [];

	/**
	 * @return IValidator[]
	 */
	public function getValidators()
	{
		return $this->validators;
	}

	/**
	 * @param string $name
	 *
	 * @return IValidator|NULL
	 */
	public function getValidator($name)
	{
		if (isset($this->validators[$name])) {
			return $this->validators[$name];
		}

		return NULL;
	}

	/**
	 * @param IValidator $validator
	 *
	 * @return $this
	 */
	public function registerValidator(IValidator $validator)
	{
		$this->validators[get_class($validator)] = $validator;

		return $this;
	}
}
