<?php
/**
 * Entity.php
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

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Mapping;

trait TEntity
{
	/**
	 * @var  Mapping\EntityHydrator
	 */
	private $hydrator;

	/**
	 * @return string
	 */
	public static function getClassName()
	{
		return get_called_class();
	}

	/**
	 * @return Mapping\EntityHydrator
	 */
	private function getHydrator()
	{
		if ($this->hydrator === NULL) {
			$this->hydrator = new Mapping\EntityHydrator();
		}

		return $this->hydrator;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->getHydrator()->extract($this);
	}

	/**
	 * @return array
	 */
	public function toSimpleArray()
	{
		return $this->getHydrator()->simpleExtract($this);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		if (isset($this->name) && $this->name !== NULL) {
			return (string)$this->name;
		} else {
			return (string)$this->id;
		}
	}
}
