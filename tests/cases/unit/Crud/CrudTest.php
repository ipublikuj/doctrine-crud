<?php declare(strict_types = 1);

namespace IPub\DoctrineCrud\Tests\Cases\Unit\Crud;

use DateTimeInterface;
use Doctrine\DBAL;
use Doctrine\ORM;
use IPub\DoctrineCrud\Tests\Cases\Unit\BaseTestCase;
use IPub\DoctrineCrud\Tests\Fixtures\Dummy\UserEntity;
use IPub\DoctrineCrud\Tests\Fixtures\Dummy\UsersManager;
use Nette\DI;
use Nette\Utils;
use const DIRECTORY_SEPARATOR;

/**
 * @testCase
 */
class CrudTest extends BaseTestCase
{

	private UsersManager $manager;

	/**
	 * @throws DI\MissingServiceException
	 */
	protected function setUp(): void
	{
		$this->additionalConfigs[] = __DIR__ . DIRECTORY_SEPARATOR . 'entities.neon';

		parent::setUp();

		$manager = $this->getContainer()->getByType(UsersManager::class);

		$this->manager = $manager;
	}

	/**
	 * @throws DI\MissingServiceException
	 * @throws ORM\Exception\ORMException
	 */
	public function testCreateEntity(): void
	{
		$this->generateDbSchema();

		$values = Utils\ArrayHash::from([
			'username' => 'tester',
			'name' => 'Tester',
		]);

		$entity = $this->manager->create($values);

		self::assertSame('tester', $entity->getUsername());
		self::assertSame('Tester', $entity->getName());
		self::assertTrue($entity->getCreatedAt() instanceof DateTimeInterface);

		$this->getEntityManager()->clear();

		$reloadedEntity = $this->getEntityManager()->getRepository(UserEntity::class)
			->find($entity->getId());

		self::assertTrue($reloadedEntity instanceof UserEntity);
		self::assertTrue($entity->getUsername() === $reloadedEntity->getUsername());
	}

	/**
	 * @throws DI\MissingServiceException
	 * @throws ORM\Exception\ORMException
	 */
	public function testCreateEntityWithEntity(): void
	{
		$this->generateDbSchema();

		$entity = new UserEntity();
		$entity->setUsername('Phantom');
		$entity->setNotWritable('Dark side');

		$values = Utils\ArrayHash::from([
			'username' => 'tester',
			'name' => 'Tester',
			'notWritable' => 'White side',
		]);

		$entity = $this->manager->create($values, $entity);

		self::assertSame('tester', $entity->getUsername());
		self::assertSame('Tester', $entity->getName());
		self::assertSame('Dark side', $entity->getNotWritable());
	}

	/**
	 * @throws DI\MissingServiceException
	 * @throws DBAL\Exception\UniqueConstraintViolationException
	 * @throws ORM\Exception\ORMException
	 */
	public function testUpdateEntity(): void
	{
		$this->generateDbSchema();

		$entity = new UserEntity();
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();

		$values = Utils\ArrayHash::from([
			'username' => 'phantom',
			'name' => 'Phantom',
			'notWritable' => 'Dark side',
		]);

		$entity = $this->manager->update($entity, $values);

		self::assertSame('tester', $entity->getUsername());
		self::assertSame('Phantom', $entity->getName());
		self::assertSame('White side', $entity->getNotWritable());
	}

	/**
	 * @throws DI\MissingServiceException
	 * @throws DBAL\Exception\UniqueConstraintViolationException
	 * @throws ORM\Exception\ORMException
	 */
	public function testDeleteEntity(): void
	{
		$this->generateDbSchema();

		$entity = new UserEntity();
		$entity->setUsername('tester');
		$entity->setName('Tester');
		$entity->setNotWritable('White side');

		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();

		$id = $entity->getId();

		$entity = $this->getEntityManager()->getRepository(UserEntity::class)
			->find($id);

		self::assertTrue($entity instanceof UserEntity);

		$this->manager->delete($entity);

		$entity = $this->getEntityManager()->getRepository(UserEntity::class)
			->find($id);

		self::assertNull($entity);
	}

}
