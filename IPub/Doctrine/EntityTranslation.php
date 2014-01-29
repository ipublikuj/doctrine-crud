<?php
/**
 * EntityTranslation.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	common
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Basic translation entity
 *
 * @ORM\MappedSuperclass
 */
abstract class EntityTranslation extends \Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation
{
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(type="integer", name="translation_id")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @var string $locale
	 *
	 * @ORM\Column(type="string", name="translation_locale", length=8)
	 */
	protected $locale;

	/**
	 * @var string $objectClass
	 *
	 * @ORM\Column(name="object_class", type="string", length=255)
	 */
	protected $objectClass;

	/**
	 * @var string $field
	 *
	 * @ORM\Column(type="string", name="translation_field", length=32)
	 */
	protected $field;

	/**
	 * @var string $foreignKey
	 *
	 * @ORM\Column(name="translation_foreign_key", type="string", length=64)
	 */
	protected $foreignKey;

	/**
	 * @var text $content
	 *
	 * @ORM\Column(type="text", name="translation_content", nullable=TRUE)
	 */
	protected $content;

	/**
	 * Convinient constructor
	 *
	 * @param string $locale
	 * @param string $field
	 * @param string $value
	 */
	public function __construct($locale, $field, $value)
	{
		$this->setLocale($locale);
		$this->setField($field);
		$this->setContent($value);
	}
}