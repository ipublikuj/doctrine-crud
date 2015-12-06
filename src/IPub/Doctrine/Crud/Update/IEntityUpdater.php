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
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;

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
	 * @param Entities\IEntity|mixed $entity
	 *
	 * @return Entities\IEntity
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function update(Utils\ArrayHash $values, $entity);
}