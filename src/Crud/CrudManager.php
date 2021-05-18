<?php declare(strict_types = 1);

/**
 * CrudManager.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\Crud;

use Closure;
use Doctrine\ORM;
use Doctrine\Persistence;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use Nette;

/**
 * Doctrine CRUD entities manager
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @phpstan-template TEntityClass of Entities\IEntity
 */
abstract class CrudManager
{

	use Nette\SmartObject;

	/** @var Closure[] */
	public array $beforeAction = [];

	/** @var Closure[] */
	public array $afterAction = [];

	/** @var string */
	protected string $entityName;

	/**
	 * @var Persistence\ObjectRepository
	 *
	 * @phpstan-var Persistence\ObjectRepository<TEntityClass>
	 */
	protected Persistence\ObjectRepository $entityRepository;

	/** @var ORM\EntityManagerInterface */
	protected Persistence\ObjectManager $entityManager;

	/** @var bool */
	private bool $flush = true;

	/**
	 * @param string $entityName
	 * @param Persistence\ManagerRegistry $managerRegistry
	 *
	 * @phpstan-param class-string<TEntityClass> $entityName
	 */
	public function __construct(
		string $entityName,
		Persistence\ManagerRegistry $managerRegistry
	) {
		$this->entityName = $entityName;

		$entityManager = $managerRegistry->getManagerForClass($entityName);

		if ($entityManager === null || !$entityManager instanceof ORM\EntityManagerInterface) {
			throw new Exceptions\InvalidStateException('Entity manager could not be loaded');
		}

		$this->entityManager = $entityManager;
		$this->entityRepository = $this->entityManager->getRepository($entityName);
	}

	/**
	 * @return bool
	 */
	public function getFlush(): bool
	{
		return $this->flush;
	}

	/**
	 * @param bool $flush
	 *
	 * @return void
	 */
	public function setFlush(bool $flush): void
	{
		$this->flush = $flush;
	}

	/**
	 * @param callable[] $hooks
	 * @param mixed[] $args
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	protected function processHooks(array $hooks, array $args = []): void
	{
		foreach ($hooks as $hook) {
			call_user_func_array($hook, $args);
		}
	}

}
