<?php
/**
 * EntityDeleter.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\Doctrine\Crud\Delete;

use Doctrine\Common\Persistence;

use IPub;
use IPub\Doctrine\Crud;
use IPub\Doctrine\Entities;
use IPub\Doctrine\Exceptions;

/**
 * Doctrine CRUD entity deleter
 *
 * @package        iPublikuj:Doctrine!
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

		$this->entityManager->remove($entity);

		$this->processHooks($this->afterAction);

		if ($this->getFlush() === TRUE) {
			$this->entityManager->flush();
		}

		return TRUE;
	}
}
