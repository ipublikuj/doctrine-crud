<?php
/**
 * Validators.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	common
 * @since		5.0
 *
 * @date		06.12.15
 */

namespace IPub\Doctrine;

use Nette;

class Validators extends Nette\Object
{
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