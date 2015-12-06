<?php
/**
 * IEntityCreator.php
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

namespace IPub\Doctrine\Crud\Create;

use Nette;
use Nette\Utils;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;

interface IEntityCreator
{
	/**
	 * @param bool $flush
	 *
	 * @return $this
	 */
	public function setFlush($flush);

	/**
	 * @param Utils\ArrayHash $values
	 * @param Entities\IEntity $entity
	 *
	 * @return Entities\IEntity
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function create(Utils\ArrayHash $values, Entities\IEntity $entity = NULL);
}