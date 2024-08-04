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

use Doctrine\ORM;
use Doctrine\Persistence;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use Nette;

/**
 * Doctrine CRUD entities manager
 *
 * @template T of Entities\IEntity
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
abstract class CrudManager
{

	use Nette\SmartObject;

	/** @var Persistence\ObjectRepository<T> */
	protected Persistence\ObjectRepository $entityRepository;

	/** @var ORM\EntityManagerInterface */
	protected Persistence\ObjectManager $entityManager;

	private bool $flush = true;

	/**
	 * @param class-string<T> $entityName
	 *
	 * @throws Exceptions\InvalidState
	 */
	public function __construct(
		protected string $entityName,
		Persistence\ManagerRegistry $managerRegistry,
	)
	{
		$entityManager = $managerRegistry->getManagerForClass($entityName);

		if (!$entityManager instanceof ORM\EntityManagerInterface) {
			throw new Exceptions\InvalidState('Entity manager could not be loaded');
		}

		$this->entityManager = $entityManager;
		$this->entityRepository = $this->entityManager->getRepository($entityName);
	}

	public function getFlush(): bool
	{
		return $this->flush;
	}

	public function setFlush(bool $flush): void
	{
		$this->flush = $flush;
	}

}
