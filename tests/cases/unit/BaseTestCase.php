<?php declare(strict_types = 1);

namespace IPub\DoctrineCrud\Tests\Cases\Unit;

use Doctrine\ORM;
use IPub\DoctrineCrud;
use Nette;
use Nette\DI;
use Nettrine;
use PHPUnit\Framework\TestCase;
use function file_exists;
use function md5;
use function time;

abstract class BaseTestCase extends TestCase
{

	/** @var array<string> */
	protected array $additionalConfigs = [];

	protected DI\Container $container;

	protected ORM\EntityManagerInterface|null $em = null;

	protected function setUp(): void
	{
		parent::setUp();

		$this->container = $this->createContainer($this->additionalConfigs);
	}

	protected function getContainer(): DI\Container
	{
		return $this->container;
	}

	/**
	 * @throws DI\MissingServiceException
	 */
	protected function getEntityManager(): ORM\EntityManagerInterface
	{
		if ($this->em === null) {
			$this->em = $this->getContainer()->getByType(Nettrine\ORM\EntityManagerDecorator::class);
		}

		return $this->em;
	}

	/**
	 * @throws ORM\Tools\ToolsException
	 * @throws DI\MissingServiceException
	 */
	protected function generateDbSchema(): void
	{
		$schema = new ORM\Tools\SchemaTool($this->getEntityManager());
		$schema->createSchema($this->getEntityManager()->getMetadataFactory()
			->getAllMetadata());
	}

	/**
	 * @param array<string> $additionalConfigs
	 */
	protected function createContainer(array $additionalConfigs = []): Nette\DI\Container
	{
		$rootDir = __DIR__ . '/../../';

		$config = new Nette\Bootstrap\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addStaticParameters(['container' => ['class' => 'SystemContainer_' . md5((string) time())]]);
		$config->addStaticParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . '/../../common.neon');

		foreach ($additionalConfigs as $additionalConfig) {
			if (file_exists($additionalConfig)) {
				$config->addConfig($additionalConfig);
			}
		}

		DoctrineCrud\DI\DoctrineCrudExtension::register($config);

		return $config->createContainer();
	}

	protected function mockContainerService(
		string $serviceType,
		object $serviceMock,
	): void
	{
		$foundServiceNames = $this->getContainer()->findByType($serviceType);

		foreach ($foundServiceNames as $serviceName) {
			$this->replaceContainerService($serviceName, $serviceMock);
		}
	}

	private function replaceContainerService(string $serviceName, object $service): void
	{
		$this->getContainer()->removeService($serviceName);
		$this->getContainer()->addService($serviceName, $service);
	}

}
