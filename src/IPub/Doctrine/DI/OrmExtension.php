<?php
/**
 * OrmExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\Doctrine\DI;

use Nette;
use Nette\PhpGenerator;

use Kdyby;

use IPub\Doctrine;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD extension container
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class OrmExtension extends Kdyby\Doctrine\DI\OrmExtension
{
	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('validators'))
			->setClass(Doctrine\Validators::CLASS_NAME);

		$builder->addDefinition($this->prefix('entity.mapper'))
			->setClass(Mapping\EntityMapper::CLASS_NAME);

		$builder->addDefinition($this->prefix('entity.crudFactory'))
			->setClass(Crud\EntityCrudFactory::CLASS_NAME);

		// syntax sugar for config
		$builder->addDefinition($this->prefix('crud'))
			->setClass(Crud\EntityCrud::CLASS_NAME)
			->setFactory('@IPub\Doctrine\Crud\EntityCrudFactory::createEntityCrud', [new PhpGenerator\PhpLiteral('$entityName')])
			->setParameters(['entityName']);

		parent::loadConfiguration();

		$configuration = $builder->getDefinition('doctrine.default.ormConfiguration');
		$configuration->addSetup('addCustomStringFunction', ['DATE_FORMAT', Doctrine\StringFunctions\DateFormat::CLASS_NAME]);
	}

	public function beforeCompile()
	{
		parent::beforeCompile();

		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get validators service
		$factory = $builder->getDefinition($this->prefix('validators'));

		foreach (array_keys($builder->findByType(Doctrine\IValidator::INTERFACE_NAME)) as $serviceName) {
			// Register validator to service
			$factory->addSetup('registerValidator', ['@' . $serviceName, $serviceName]);
		}
	}
}
