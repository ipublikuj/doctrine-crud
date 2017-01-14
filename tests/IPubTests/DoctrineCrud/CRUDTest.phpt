<?php
/**
 * Test: IPub\DoctrineCrud\Creator
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.16
 */

declare(strict_types = 1);

namespace IPubTests\Doctrine;

use Nette;
use Nette\Utils;

use Kdyby;

use Tester;
use Tester\Assert;

use Doctrine;
use Doctrine\ORM;
use Doctrine\Common;

use IPub;

use IPubTests\Doctrine\Models;

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
	 * @var Kdyby\Doctrine\EntityManager
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

		Assert::true($entity instanceof Models\UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Tester', $entity->getName());
		Assert::true($entity->getCreatedAt() instanceof \DateTime);

		$this->em->clear();

		$reloadedEntity = $this->em->getRepository('IPubTests\Doctrine\Models\UserEntity')->find($entity->getId());

		Assert::true($reloadedEntity instanceof Models\UserEntity);
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

		Assert::true($entity instanceof Models\UserEntity);
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

		Assert::true($entity instanceof Models\UserEntity);
		Assert::same('tester', $entity->getUsername());
		Assert::same('Phantom', $entity->getName());
		Assert::same('White side', $entity->getNotWritable());
	}

	public function testDeleteEntity()
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity;
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->em->persist($entity);
		$this->em->flush();

		$id = $entity->getId();

		$entity = $this->em->getRepository('IPubTests\Doctrine\Models\UserEntity')->find($id);

		Assert::true($entity instanceof Models\UserEntity);

		$this->manager->delete($entity);

		$entity = $this->em->getRepository('IPubTests\Doctrine\Models\UserEntity')->find($id);

		Assert::null($entity);
	}

	public function testEntityTraits()
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

		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5((string) time())]]);
		$config->addParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . DS . 'files' . DS . 'config.neon');
		$config->addConfig(__DIR__ . DS . 'files' . DS . 'entities.neon');

		return $config->createContainer();
	}
}

\run(new CRUDTest());