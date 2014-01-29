<?php
/**
 * Context.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\DI;

use Nette\Object;
use IPub\Doctrine\IValidator;
use Nette\InvalidStateException;
use Nette\DI\Container;

class Context extends Object implements IContext
{
	/**
	 * @var  Container
	 */
	private $container;

	/**
	 * @param Container $container
	 */
	function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @param $class
	 *
	 * @return IValidator
	 *
	 * @throws \Nette\InvalidStateException
	 */
	public function getValidator($class)
	{
		$validator = $this->container->getByType($class);

		if ( $validator instanceof IValidator ) {
			return $validator;
		}

		throw new InvalidStateException('Object "' . $class . '" is not instance of IPub\Doctrine\IValidator');
	}
}