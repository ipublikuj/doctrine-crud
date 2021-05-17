<?php declare(strict_types = 1);

namespace Tests\Cases\Models;

use IPub\DoctrineCrud\Crud;
use Nette;
use Nette\Utils;

class ArticlesManager
{

	use Nette\SmartObject;

	/**
	 * @var Crud\IEntityCrud
	 */
	private Crud\IEntityCrud $entityCrud;

	/**
	 * @param Crud\IEntityCrud $entityCrud
	 */
	public function __construct(
		Crud\IEntityCrud $entityCrud
	) {
		// Entity CRUD for handling entities
		$this->entityCrud = $entityCrud;
	}

	/**
	 * @param Utils\ArrayHash $values
	 * @param ArticleEntity|null $entity
	 *
	 * @return ArticleEntity
	 */
	public function create(Utils\ArrayHash $values, ?ArticleEntity $entity = null): ArticleEntity
	{
		// Get entity creator
		$creator = $this->entityCrud->getEntityCreator();

		// Assign before create entity events
		$creator->beforeAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Assign after create entity events
		$creator->afterAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Create new entity
		return $creator->create($values, $entity);
	}

	/**
	 * @param ArticleEntity|mixed $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return ArticleEntity
	 */
	public function update(ArticleEntity $entity, Utils\ArrayHash $values): ArticleEntity
	{
		// Get entity updater
		$updater = $this->entityCrud->getEntityUpdater();

		// Assign before update entity events
		$updater->beforeAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Assign after create entity events
		$updater->afterAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Update entity in database
		return $updater->update($values, $entity);
	}

	/**
	 * @param ArticleEntity|mixed $entity
	 *
	 * @return bool
	 */
	public function delete(ArticleEntity $entity): bool
	{
		// Get entity deleter
		$deleter = $this->entityCrud->getEntityDeleter();

		// Assign before delete entity events
		$deleter->beforeAction[] = function (ArticleEntity $entity): void {
		};

		// Assign after delete entity events
		$deleter->afterAction[] = function (): void {
		};

		// Delete entity from database
		return $deleter->delete($entity);
	}

	/**
	 * @return Crud\IEntityCrud
	 */
	public function getEntityCrud(): Crud\IEntityCrud
	{
		return $this->entityCrud;
	}

}
