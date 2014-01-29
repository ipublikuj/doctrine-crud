<?php
/**
 * IContext.php
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

use IPub\Doctrine\IValidator;

interface IContext 
{
	/**
	 * @param $class
	 *
	 * @return IValidator
	 */
	public function getValidator($class);
} 