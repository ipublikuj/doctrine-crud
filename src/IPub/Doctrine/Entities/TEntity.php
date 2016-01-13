<?php
/**
 * Entity.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Entities
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine\Entities;

use IPub;
use IPub\Doctrine;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD identified entity helper trait
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @ORM\MappedSuperclass
 */
trait TEntity
{
	/**
	 * @var  Mapping\EntityHydrator
	 */
	protected $hydrator;

	/**
	 * @return string
	 */
	public static function getClassName()
	{
		return get_called_class();
	}

	/**
	 * @param int $maxLevel
	 *
	 * @return array
	 */
	public function toArray($maxLevel = 1)
	{
		return $this->getHydrator()->extract($this, $maxLevel);
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
			return (string) $this->name;

		} else if (property_exists($this, 'id')) {
			return $this->id;

		} else {
			return '';
		}
	}

	/**
	 * @return Mapping\EntityHydrator
	 */
	protected function getHydrator()
	{
		if ($this->hydrator === NULL) {
			$this->hydrator = new Mapping\EntityHydrator;
		}

		return $this->hydrator;
	}
}
