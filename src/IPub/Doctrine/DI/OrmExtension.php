<?php
/**
 * OrmExtension.php
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
use Nette\PhpGenerator;

use Kdyby;

class OrmExtension extends Kdyby\Doctrine\DI\OrmExtension
{
	/**
	 * @var array
	 */
	public $defaults = [
		'defaultRepositoryClassName' => 'IPub\Doctrine\EntityRepository'
	];

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		// Merge extension configuration with kdyby/doctrine configuration
		$this->managerDefaults = array_merge($this->managerDefaults, $this->defaults);

		// Get container builder
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('validators'))
			->setClass('IPub\Doctrine\Validators');

		$builder->addDefinition($this->prefix('entity.hydrator'))
			->setClass('IPub\Doctrine\Mapping\EntityHydrator');

		$builder->addDefinition($this->prefix('entity.mapper'))
			->setClass('IPub\Doctrine\Mapping\EntityMapper');

		$builder->addDefinition($this->prefix('entity.crudFactory'))
			->setClass('IPub\Doctrine\Crud\EntityCrudFactory');

		// syntax sugar for config
		$builder->addDefinition($this->prefix('crud'))
			->setClass('IPub\Doctrine\Crud\EntityCrud')
			->setFactory('@IPub\Doctrine\Crud\EntityCrudFactory::createEntityCrud', [new PhpGenerator\PhpLiteral('$entityName')])
			->setParameters(['entityName']);

		parent::loadConfiguration();

		$configuration = $builder->getDefinition('doctrine.default.ormConfiguration');
		$configuration->addSetup('addCustomStringFunction', ['DATE_FORMAT', 'IPub\Doctrine\StringFunctions\DateFormat']);
	}

	public function beforeCompile()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get validators service
		$factory = $builder->getDefinition($this->prefix('validators'));

		foreach (array_keys($builder->findByType('IPub\Doctrine\IValidator')) as $serviceName) {
			// Register validator to service
			$factory->addSetup('registerValidator', ['@'. $serviceName, $serviceName]);
		}
	}
}