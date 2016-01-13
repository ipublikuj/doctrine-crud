<?php
/**
 * Test: IPub\Doctrine\Extension
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           13.01.16
 */

namespace IPubTests\Doctrine;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\Doctrine;

require __DIR__ . '/../bootstrap.php';

/**
 * Registering doctrine blameable extension tests
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class ExtensionTest extends Tester\TestCase
{
	public function testFunctional()
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('doctrine.validators') instanceof Doctrine\Validators);
		Assert::true($dic->getService('doctrine.entity.mapper') instanceof Doctrine\Mapping\EntityMapper);
		Assert::true($dic->getService('doctrine.entity.crudFactory') instanceof Doctrine\Crud\EntityCrudFactory);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer()
	{
		$rootDir = __DIR__ . '/../../';

		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5(time())]]);
		$config->addParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . '/files/config.neon', !isset($config->defaultExtensions['nette']) ? 'v23' : 'v22');

		Doctrine\DI\OrmExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
