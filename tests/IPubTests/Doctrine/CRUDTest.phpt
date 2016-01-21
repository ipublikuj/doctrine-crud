<?php
/**
 * Test: IPub\Doctrine\Creator
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

namespace IPubTests\Doctrine;

use IPubTests\Doctrine\Models\UserEntity;
use Nette;
use Nette\Utils;

use Tester;
use Tester\Assert;

use Doctrine;
use Doctrine\ORM;
use Doctrine\Common;

use IPub;

require __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/models/UserEntity.php';
require_once __DIR__ . '/models/UsersManager.php';

/**
 * Creating entity tests
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class CRUDTest extends Tester\TestCase
{
	/**
	 * @var \Nette\DI\Container
	 */
	private $container;

	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;

	/**
	 * @var Models\UsersManager
	 */
	private $manager;

	protected function setUp()
	{
		parent::setUp();

		$this->container = $this->createContainer();
		$this->em = $this->container->getByType('Kdyby\Doctrine\EntityManager');
		$this->manager = $this->container->getByType('IPubTests\Doctrine\Models\UsersManager');
	}

	public function testCreateEntity()
	{
		$this->generateDbSchema();

		$values = Utils\ArrayHash::from([
			'username' => 'tester',
			'name'     => 'Tester',
		]);

		$entity = $this->manager->create($values);

		Assert::true($entity instanceof UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Tester', $entity->getName());
		Assert::true($entity->getCreatedAt() instanceof \DateTime);

		$this->em->clear();

		$reloadedEntity = $this->em->getRepository('IPubTests\Doctrine\Models\UserEntity')->find($entity->getId());

		Assert::true($reloadedEntity instanceof UserEntity);
		Assert::true($entity->getUsername() === $reloadedEntity->getUsername());
	}

	public function testCreateEntityWithEntity()
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity;
		$entity->setUsername('Phantom');
		$entity->setNotWritable('Dark side');

		$values = Utils\ArrayHash::from([
			'username'    => 'tester',
			'name'        => 'Tester',
			'notWritable' => 'White side',
		]);

		$entity = $this->manager->create($values, $entity);

		Assert::true($entity instanceof UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Tester', $entity->getName());
		Assert::same('Dark side', $entity->getNotWritable());
	}

	public function testUpdateEntity()
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity;
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->em->persist($entity);
		$this->em->flush();

		$values = Utils\ArrayHash::from([
			'username'    => 'phantom',
			'name'        => 'Phantom',
			'notWritable' => 'Dark side',
		]);

		$entity = $this->manager->update($entity, $values);

		Assert::true($entity instanceof UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Phantom', $entity->getName());
		Assert::same('White side', $entity->getNotWritable());
	}

	public function testEntityTraits()
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity;
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->em->persist($entity);
		$this->em->flush();

		Assert::same((string) $entity->getId(), (string) $entity);
		Assert::same([
			'id'          => $entity->getId(),
			'username'    => 'tester',
			'name'        => 'Tester',
			'notWritable' => 'White side',
			'createdAt'   => NULL,
			'updateddAt'  => NULL,
		], $entity->toArray());
		Assert::same([
			'id'          => $entity->getId(),
			'username'    => 'tester',
			'name'        => 'Tester',
			'notWritable' => 'White side',
			'createdAt'   => NULL,
			'updateddAt'  => NULL,
		], $entity->toSimpleArray());
	}

	private function generateDbSchema()
	{
		$schema = new ORM\Tools\SchemaTool($this->em);
		$schema->createSchema($this->em->getMetadataFactory()->getAllMetadata());
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
		$config->addConfig(__DIR__ . '/files/entities.neon', $config::NONE);

		return $config->createContainer();
	}
}

\run(new CRUDTest());
