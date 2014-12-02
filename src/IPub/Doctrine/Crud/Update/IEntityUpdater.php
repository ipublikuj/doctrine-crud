<?php
/**
 * IEntityUpdater.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	Crud
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\Crud\Update;

use Nette;
use Nette\Utils;

use IPub;
use IPub\Doctrine;

interface IEntityUpdater
{
	/**
	 * @param bool $flush
	 *
	 * @return $this
	 */
	public function setFlush($flush);

	/**
	 * @param Utils\ArrayHash $values
	 * @param Doctrine\IEntity|int $entity
	 *
	 * @return Doctrine\IEntity
	 *
	 * @throws Nette\InvalidArgumentException
	 */
	public function update(Utils\ArrayHash $values, $entity);
}