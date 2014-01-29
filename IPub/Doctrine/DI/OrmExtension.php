<?php
/**
 * OrmExtension.php
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

use Nette\PhpGenerator\PhpLiteral;

class OrmExtension extends \Kdyby\Doctrine\DI\OrmExtension
{
	/**
	 * @var array
	 */
	public $defaults = array(
		'defaultRepositoryClassName' => 'IPub\Doctrine\EntityDao'
	);

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		$this->managerDefaults = array_merge($this->managerDefaults, $this->defaults);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('context'))
			->setClass('\IPub\Doctrine\DI\Context');

		$builder->addDefinition($this->prefix('entityHydrator'))
			->setClass('IPub\Doctrine\Mapping\EntityHydrator');

		$builder->addDefinition($this->prefix('entityMapper'))
			->setClass('IPub\Doctrine\Mapping\EntityMapper');

		$builder->addDefinition($this->prefix('entityCrudFactory'))
			->setClass('IPub\Doctrine\Crud\EntityCrudFactory');

		// syntax sugar for config
		$builder->addDefinition($this->prefix('crud'))
			->setClass('IPub\Doctrine\Crud\EntityCrud')
			->setFactory('@IPub\Doctrine\Crud\EntityCrudFactory::createEntityCrud', array(new PhpLiteral('$entityName')))
			->setParameters(array('entityName'));

		parent::loadConfiguration();

		$configuration = $builder->getDefinition('doctrine.default.ormConfiguration');
		$configuration->addSetup('addCustomStringFunction', array('DATE_FORMAT', 'IPub\Doctrine\StringFunctions\DateFormat'));
	}
}