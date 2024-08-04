<?php declare(strict_types = 1);

namespace IPub\DoctrineCrud\Tests\Fixtures\Dummy;

use IPub\DoctrineCrud\Crud;
use Nette;
use Nette\Utils;

class ArticlesManager
{

	use Nette\SmartObject;

	public function __construct(private Crud\IEntityCrud $entityCrud)
	{
		// Entity CRUD for handling entities
	}

	public function create(Utils\ArrayHash $values, ArticleEntity|null $entity = null): ArticleEntity
	{
		// Get entity creator
		$creator = $this->entityCrud->getEntityCreator();

		// Assign before create entity events
		$creator->beforeAction[] = static function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Assign after create entity events
		$creator->afterAction[] = static function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Create new entity
		return $creator->create($values, $entity);
	}

	/**
	 * @param ArticleEntity|mixed $entity
	 */
	public function update(ArticleEntity $entity, Utils\ArrayHash $values): ArticleEntity
	{
		// Get entity updater
		$updater = $this->entityCrud->getEntityUpdater();

		// Assign before update entity events
		$updater->beforeAction[] = static function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Assign after create entity events
		$updater->afterAction[] = static function (ArticleEntity $entity, Utils\ArrayHash $values): void {
		};

		// Update entity in database
		return $updater->update($values, $entity);
	}

	/**
	 * @param ArticleEntity|mixed $entity
	 */
	public function delete(ArticleEntity $entity): bool
	{
		// Get entity deleter
		$deleter = $this->entityCrud->getEntityDeleter();

		// Assign before delete entity events
		$deleter->beforeAction[] = static function (ArticleEntity $entity): void {
		};

		// Assign after delete entity events
		$deleter->afterAction[] = static function (): void {
		};

		// Delete entity from database
		return $deleter->delete($entity);
	}

	public function getEntityCrud(): Crud\IEntityCrud
	{
		return $this->entityCrud;
	}

}
