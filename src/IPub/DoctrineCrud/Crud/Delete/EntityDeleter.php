<?php
/**
 * EntityDeleter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Crud\Delete;

use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Entities;
use IPub\DoctrineCrud\Exceptions;

/**
 * Doctrine CRUD entity deleter
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method beforeAction(Entities\IEntity $entity)
 * @method afterAction()
 */
class EntityDeleter extends Crud\CrudManager
{
	/**
	 * @param Entities\IEntity|int|string $entity
	 *
	 * @return bool
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function delete($entity) : bool
	{
		if (!$entity instanceof Entities\IEntity) {
			$entity = $this->entityRepository->find($entity);
		}

		if (!$entity) {
			throw new Exceptions\InvalidArgumentException('Entity not found.');
		}

		$this->processHooks($this->beforeAction, [$entity]);

		$this->entityManager->getConnection()->beginTransaction();

		$this->entityManager->remove($entity);

		if ($this->getFlush() === TRUE) {
			$this->entityManager->flush();
		}

		$this->entityManager->getConnection()->commit();

		$this->processHooks($this->afterAction);

		return TRUE;
	}
}
