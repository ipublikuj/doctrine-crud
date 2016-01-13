<?php
/**
 * CrudManager.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine\Crud;

use Nette;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Exceptions;

abstract class CrudManager extends Nette\Object
{
	/**
	 * @var bool
	 */
	private $flush = TRUE;

	/**
	 * @param bool $flush
	 *
	 * @return $this
	 */
	public function setFlush($flush)
	{
		$this->flush = (bool) $flush;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getFlush()
	{
		return $this->flush;
	}

	/**
	 * @param array|mixed $hooks
	 * @param array $args
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	protected function processHooks($hooks, array $args = [])
	{
		if (!is_array($hooks)) {
			throw new Exceptions\InvalidStateException('Hooks configuration must be in array');
		}

		foreach ($hooks as $hook) {
			if (!is_callable($hook)) {
				throw new Exceptions\InvalidStateException('Invalid callback given.');
			}

			call_user_func_array($hook, $args);
		}
	}
}
