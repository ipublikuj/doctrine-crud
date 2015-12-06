<?php
/**
 * EntityRepository.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	common
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine;

use Nette;
use Nette\Reflection;

use Kdyby;

class EntityRepository extends Kdyby\Doctrine\EntityRepository
{
	/**
	 * @return \IPub\Doctrine\Entity
	 */
	public function createEntity()
	{
		$reflection = new Reflection\ClassType($this->getEntityName());

		return $reflection->newInstanceArgs(func_get_args());
	}
}