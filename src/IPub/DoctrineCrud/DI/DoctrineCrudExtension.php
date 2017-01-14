<?php
/**
 * DoctrineCrudExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\DI;

use Doctrine\Common;

use Nette;
use Nette\DI;
use Nette\PhpGenerator;

use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD extension container
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class DoctrineCrudExtension extends DI\CompilerExtension
{
	// Define tag string for validator
	const TAG_VALIDATOR = 'ipub.doctrine.validator';

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		$annotationReader = new Common\Annotations\AnnotationReader;

		Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
			'IPub\\Doctrine\\Entities\\IEntity'
		);

		$annotationReader = new Common\Annotations\CachedReader($annotationReader, new Common\Cache\ArrayCache);

		/**
		 * Extensions helpers
		 */

		$builder->addDefinition($this->prefix('entity.validator'))
			->setClass(DoctrineCrud\Validation\ValidatorProxy::class)
			->setArguments([$annotationReader])
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('entity.mapper'))
			->setClass(Mapping\EntityMapper::class)
			->setArguments(['@' . $this->prefix('entity.validator'), $annotationReader])
			->setAutowired(FALSE);

		/**
		 * CRUD factories
		 */

		$builder->addDefinition($this->prefix('entity.creator'))
			->setClass(Crud\Create\EntityCreator::class)
			->setImplement(Crud\Create\IEntityCreator::class)
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('entity.updater'))
			->setClass(Crud\Update\EntityUpdater::class)
			->setImplement(Crud\Update\IEntityUpdater::class)
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('entity.deleter'))
			->setClass(Crud\Delete\EntityDeleter::class)
			->setImplement(Crud\Delete\IEntityDeleter::class)
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('entity.crudFactory'))
			->setClass(Crud\EntityCrudFactory::class)
			->setArguments([
				'@' . $this->prefix('entity.mapper'),
				'@' . $this->prefix('entity.creator'),
				'@' . $this->prefix('entity.updater'),
				'@' . $this->prefix('entity.deleter'),
			]);

		// Syntax sugar for config
		$builder->addDefinition($this->prefix('crud'))
			->setClass(Crud\EntityCrud::class)
			->setFactory('@IPub\DoctrineCrud\Crud\EntityCrudFactory::create', [new PhpGenerator\PhpLiteral('$entityName')])
			->setParameters(['entityName'])
			->setAutowired(FALSE);

		/**
		 *
		 */

		$configuration = $builder->getDefinition('doctrine.default.ormConfiguration');
		$configuration->addSetup('addCustomStringFunction', ['DATE_FORMAT', DoctrineCrud\StringFunctions\DateFormat::class]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile()
	{
		parent::beforeCompile();

		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get validators service
		$validator = $builder->getDefinition($this->prefix('entity.validator'));

		foreach (array_keys($builder->findByTag(self::TAG_VALIDATOR)) as $serviceName) {
			// Register validator to proxy validator
			$validator->addSetup('registerValidator', ['@' . $serviceName, $serviceName]);
		}
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, string $extensionName = 'doctrine-crud')
	{
		$config->onCompile[] = function (Nette\Configurator $config, DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new DoctrineCrudExtension());
		};
	}
}
