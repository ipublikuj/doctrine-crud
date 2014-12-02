<?php
/**
 * IContext.php
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

use IPub;
use IPub\Doctrine;

interface IContext 
{
	/**
	 * @param $class
	 *
	 * @return Doctrine\IValidator
	 *
	 * @throws Nette\InvalidStateException
	 */
	public function getValidator($class);
} 