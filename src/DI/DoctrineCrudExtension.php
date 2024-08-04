<?php declare(strict_types = 1);

/**
 * DoctrineCrudExtension.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\DI;

use Doctrine;
use IPub\DoctrineCrud;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Mapping;
use Nette\Bootstrap;
use Nette\DI;
use Nette\PhpGenerator;

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

	public static function register(
		Bootstrap\Configurator $config,
		string $extensionName = 'doctrineCrud',
	): void
	{
		$config->onCompile[] = static function (
			Bootstrap\Configurator $config,
			DI\Compiler $compiler,
		) use ($extensionName): void {
			$compiler->addExtension($extensionName, new self());
		};
	}

	public function loadConfiguration(): void
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		/**
		 * Extensions helpers
		 */

		$builder->addDefinition($this->prefix('entity.mapper'))
			->setType(Mapping\EntityMapper::class)
			->setAutowired(false);

		/**
		 * CRUD factories
		 */

		$builder->addFactoryDefinition($this->prefix('entity.creator'))
			->setImplement(Crud\Create\IEntityCreator::class)
			->setAutowired(false)
			->getResultDefinition()
			->setType(Crud\Create\EntityCreator::class);

		$builder->addFactoryDefinition($this->prefix('entity.updater'))
			->setImplement(Crud\Update\IEntityUpdater::class)
			->setAutowired(false)
			->getResultDefinition()
			->setFactory(Crud\Update\EntityUpdater::class);

		$builder->addFactoryDefinition($this->prefix('entity.deleter'))
			->setImplement(Crud\Delete\IEntityDeleter::class)
			->setAutowired(false)
			->getResultDefinition()
			->setFactory(Crud\Delete\EntityDeleter::class);

		// Syntax sugar for config
		$builder->addFactoryDefinition($this->prefix('crud'))
			->setImplement(Crud\IEntityCrudFactory::class)
			->getResultDefinition()
			->setType(Crud\EntityCrud::class)
			->setArguments([
				new PhpGenerator\Literal('$entityName'),
				'@' . $this->prefix('entity.mapper'),
				'@' . $this->prefix('entity.creator'),
				'@' . $this->prefix('entity.updater'),
				'@' . $this->prefix('entity.deleter'),
			]);
	}

	/**
	 * @throws DI\MissingServiceException
	 */
	public function beforeCompile(): void
	{
		parent::beforeCompile();

		// Get container builder
		$builder = $this->getContainerBuilder();

		$entityManagerServiceName = $builder->getByType(Doctrine\ORM\EntityManagerInterface::class, true);

		$entityManagerService = $builder->getDefinition($entityManagerServiceName);

		if ($entityManagerService instanceof DI\Definitions\ServiceDefinition) {
			$entityManagerService->addSetup(
				'?->getConfiguration()->addCustomStringFunction(?, ?)',
				[
					'@self',
					'DATE_FORMAT',
					DoctrineCrud\StringFunctions\DateFormat::class,
				],
			);
		}
	}

}
