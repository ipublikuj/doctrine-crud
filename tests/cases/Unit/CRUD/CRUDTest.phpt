<?php declare(strict_types = 1);

namespace Tests\Cases;

use DateTimeInterface;
use Nette\Utils;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../BaseTestCase.php';

require_once __DIR__ . '/../../../libs/models/UserEntity.php';
require_once __DIR__ . '/../../../libs/models/ArticleEntity.php';
require_once __DIR__ . '/../../../libs/models/UsersManager.php';
require_once __DIR__ . '/../../../libs/models/ArticlesManager.php';

/**
 * @testCase
 */
class CRUDTest extends BaseTestCase
{

	/** @var string[] */
	protected array $additionalConfigs = [
		__DIR__ . DIRECTORY_SEPARATOR . 'entities.neon',
	];

	/** @var Models\UsersManager */
	private Models\UsersManager $manager;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp(): void
	{
		parent::setUp();

		/** @var Models\UsersManager $manager */
		$manager = $this->getContainer()->getByType(Models\UsersManager::class);

		$this->manager = $manager;
	}

	public function testCreateEntity(): void
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
		Assert::true($entity->getCreatedAt() instanceof DateTimeInterface);

		$this->getEntityManager()->clear();

		$reloadedEntity = $this->getEntityManager()->getRepository(Models\UserEntity::class)
			->find($entity->getId());

		Assert::true($reloadedEntity instanceof Models\UserEntity);
		Assert::true($entity->getUsername() === $reloadedEntity->getUsername());
	}

	public function testCreateEntityWithEntity(): void
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity();
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

	public function testUpdateEntity(): void
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity();
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();

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

	public function testDeleteEntity(): void
	{
		$this->generateDbSchema();

		$entity = new Models\UserEntity();
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();

		$id = $entity->getId();

		$entity = $this->getEntityManager()->getRepository(Models\UserEntity::class)
			->find($id);

		Assert::true($entity instanceof Models\UserEntity);

		$this->manager->delete($entity);

		$entity = $this->getEntityManager()->getRepository(Models\UserEntity::class)
			->find($id);

		Assert::null($entity);
	}

}

$test_case = new CRUDTest();
$test_case->run();
