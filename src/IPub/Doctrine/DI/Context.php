<?php
/**
 * Context.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\DI;

use Nette;
use Nette\DI;

use IPub;
use IPub\Doctrine;

class Context extends Nette\Object implements IContext
{
	/**
	 * @var  DI\Container
	 */
	private $container;

	/**
	 * @param DI\Container $container
	 */
	function __construct(DI\Container $container)
	{
		$this->container = $container;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValidator($class)
	{
		$validator = $this->container->getByType($class);

		if ($validator instanceof Doctrine\IValidator) {
			return $validator;
		}

		throw new Nette\InvalidStateException('Object "' . $class . '" is not instance of IPub\Doctrine\IValidator');
	}
}