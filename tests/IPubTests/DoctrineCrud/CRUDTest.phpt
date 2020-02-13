<?php
/**
 * Test: IPub\DoctrineCrud\Creator
 *
 * @testCase
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

declare(strict_types = 1);

namespace IPubTests\DoctrineCrud;

use Nette;
use Nette\Utils;

use Doctrine\ORM;

use Nettrine;

use Tester;
use Tester\Assert;

use IPub\DoctrineCrud;

use IPubTests\DoctrineCrud\Models;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once __DIR__ . DS . 'models' . DS . 'UserEntity.php';
require_once __DIR__ . DS . 'models' . DS . 'ArticleEntity.php';
require_once __DIR__ . DS . 'models' . DS . 'UsersManager.php';
require_once __DIR__ . DS . 'models' . DS . 'ArticlesManager.php';

/**
 * Creating entity tests
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class CRUDTest extends Tester\TestCase
{
	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var ORM\EntityManager
	 */
	private $em;

	/**
	 * @var Models\UsersManager
	 */
	private $manager;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp() : void
	{
		parent::setUp();

		$this->container = $this->createContainer();
		$this->em = $this->container->getByType(Nettrine\ORM\EntityManagerDecorator::class);
		$this->manager = $this->container->getByType(Models\UsersManager::class);
	}

	public function testCreateEntity() : void
	{
		$this->generateDbSchema();

		$values = Utils\ArrayHash::from([
			'username' => 'tester',
			'name'     => 'Tester',
		]);

		$entity = $this->manager->create($values);

		Assert::true($entity instanceof Models\UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Tester', $entity->getName());
		Assert::true($entity->getCreatedAt() instanceof \DateTime);

		$this->em->clear();

		$reloadedEntity = $this->em->getRepository(Models\UserEntity::class)->find($entity->getId());

		Assert::true($reloadedEntity instanceof Models\UserEntity);
		Assert::true($entity->getUsername() === $reloadedEntity->getUsername());
	}

	public function testCreateEntityWithEntity() : void
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

		Assert::true($entity instanceof Models\UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Tester', $entity->getName());
		Assert::same('Dark side', $entity->getNotWritable());
	}

	public function testUpdateEntity() : void
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

		Assert::true($entity instanceof Models\UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Phantom', $entity->getName());
		Assert::same('White side', $entity->getNotWritable());
	}

	public function testDeleteEntity() : void
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity;
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->em->persist($entity);
		$this->em->flush();

		$id = $entity->getId();

		$entity = $this->em->getRepository(Models\UserEntity::class)->find($id);

		Assert::true($entity instanceof Models\UserEntity);

		$this->manager->delete($entity);

		$entity = $this->em->getRepository(Models\UserEntity::class)->find($id);

		Assert::null($entity);
	}

	public function testEntityTraits() : void
	{
		$this->generateDbSchema();

		$user = new Models\UserEntity;
		$user->setUsername('tester');
		$user->setName('Tester');
		$user->setNotWritable('White side');

		$this->em->persist($user);
		$this->em->flush();

		$article = new Models\ArticleEntity();
		$article->setTitle('Testing article');
		$article->setOwner($user);

		$this->em->persist($article);
		$this->em->flush();

		Assert::same((string) $user->getName(), (string) $user, 'UserEntity toString');
		Assert::same('', (string) $article, 'ArticleEntity toString');

		Assert::same([
			'id'          => $user->getId(),
			'username'    => 'tester',
			'name'        => 'Tester',
			'notWritable' => 'White side',
			'createdAt'   => NULL,
			'updatedAt'   => NULL,
		], $user->toArray(), 'UserEntity - toArray()');
		Assert::same([
			'id'          => $user->getId(),
			'username'    => 'tester',
			'name'        => 'Tester',
			'notWritable' => 'White side',
			'createdAt'   => NULL,
			'updatedAt'   => NULL,
		], $user->toSimpleArray(), 'UserEntity - toSimpleArray()');

		Assert::same([
			'title' => 'Testing article',
			'owner' => $user,
		], $article->toArray(), 'ArticleEntity - toArray()');
		Assert::same([
			'title' => 'Testing article',
			'owner' => [
				'id'          => $user->getId(),
				'username'    => 'tester',
				'name'        => 'Tester',
				'notWritable' => 'White side',
				'createdAt'   => NULL,
				'updatedAt'   => NULL,
			],
		], $article->toArray(2), 'ArticleEntity - toArray(2)');
		Assert::same([
			'title' => 'Testing article',
			'owner' => $user->getId(),
		], $article->toSimpleArray(), 'ArticleEntity - toSimpleArray()');
	}

	/**
	 * @return void
	 */
	private function generateDbSchema() : void
	{
		$schema = new ORM\Tools\SchemaTool($this->em);
		$schema->createSchema($this->em->getMetadataFactory()->getAllMetadata());
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$rootDir = __DIR__ . '/../../';

		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5((string) time())]]);
		$config->addParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . DS . 'files' . DS . 'config.neon');
		$config->addConfig(__DIR__ . DS . 'files' . DS . 'entities.neon');

		DoctrineCrud\DI\DoctrineCrudExtension::register($config);

		return $config->createContainer();
	}
}

\run(new CRUDTest());
