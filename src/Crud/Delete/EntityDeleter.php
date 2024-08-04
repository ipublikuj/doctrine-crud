<?php declare(strict_types = 1);

/**
 * EntityDeleter.php
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

namespace IPub\DoctrineCrud\Crud\Delete;

use Doctrine\DBAL;
use Doctrine\ORM;
use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;
use Nette\Utils;

/**
 * Doctrine CRUD entity deleter
 *
 * @template   T of Entities\IEntity
 * @extends    Crud\CrudManager<T>
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class EntityDeleter extends Crud\CrudManager
{

	/** @var array<callable(Entities\IEntity): void> */
	public array $beforeAction = [];

	/** @var array<callable(): void> */
	public array $afterAction = [];

	/**
	 * @throws Exceptions\InvalidArgument
	 * @throws Exceptions\InvalidState
	 */
	public function delete(Entities\IEntity|int|string $entity): bool
	{
		if (!$entity instanceof Entities\IEntity) {
			$entity = $this->entityRepository->find($entity);
		}

		if (!$entity instanceof Entities\IEntity) {
			throw new Exceptions\InvalidArgument('Entity not found.');
		}

		Utils\Arrays::invoke($this->beforeAction, $entity);

		try {
			$this->entityManager->getConnection()->beginTransaction();

			$this->entityManager->remove($entity);

			if ($this->getFlush() === true) {
				$this->entityManager->flush();
			}

			$this->entityManager->getConnection()->commit();
		} catch (ORM\Exception\ORMException | DBAL\Exception $ex) {
			throw new Exceptions\InvalidState('Entity could not be deleted', $ex->getCode(), $ex);
		}

		Utils\Arrays::invoke($this->afterAction);

		return true;
	}

}
