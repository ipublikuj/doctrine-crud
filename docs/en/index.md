# Quickstart

This Doctrine extension brings you easier way how to **C**reate, **R**ead, **U**pdate and **D**elete entities with [Kdyby/Doctrine](https://github.com/Kdyby/Doctrine)

> NOTE: reading is not implemented, because it depends on project what you are programming.

## Installation

The best way to install ipub/doctrine is using [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/doctrine
```

After that you have to register extension in config.neon.

```neon
extensions:
	doctrine: IPub\DoctrineCrud\DI\OrmExtension
```

This extensions extends [Kdyby/Doctrine](https://github.com/Kdyby/Doctrine) extensions, so therefore you have to remove extensions definition for Kdyby/Doctrine. Configuration is same as for Kdyby/Doctrine.

## Usage

At first you have to create CRUD service for given entity. It can be easily created in neon configuration:

```neon
services:
	yourEntityCrud:
		factory: @doctrine.crud(Your\Cool\NameSpace\YourEntity)
```

Now CRUD service for given entity is created and now you can create a manager for this entity:

```php
class YourEntityManager extends \Nette\Object
{
	/**
	 * @var \IPub\DoctrineCrud\Crud\IEntityCrud
	 */
	private $entityCrud;

	/**
	 * @param \IPub\DoctrineCrud\Crud\IEntityCrud $entityCrud
	 */
	function __construct(
		\IPub\DoctrineCrud\Crud\IEntityCrud $entityCrud
	) {
		// Entity CRUD for handling entities
		$this->entityCrud = $entityCrud;
	}

	/**
	 * @param \Nette\Utils\ArrayHash $values
	 * @param YourEntity|NULL $entity
	 *
	 * @return ArticleEntity
	 */
	public function create(\Nette\Utils\ArrayHash $values, $entity = NULL)
	{
		// Get entity creator
		$creator = $this->entityCrud->getEntityCreator();

		// Assign before create entity events
		$creator->beforeAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values) {
		};

		// Assign after create entity events
		$creator->afterAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values) {
		};

		// Create new entity
		return $creator->create($values, $entity);
	}

	/**
	 * @param ArticleEntity|mixed $entity
	 * @param \Nette\Utils\ArrayHash $values
	 *
	 * @return YourEntity
	 */
	public function update($entity, Utils\ArrayHash $values)
	{
		// Get entity updater
		$updater = $this->entityCrud->getEntityUpdater();

		// Assign before update entity events
		$updater->beforeAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values) {
		};

		// Assign after create entity events
		$updater->afterAction[] = function (ArticleEntity $entity, Utils\ArrayHash $values) {
		};

		// Update entity in database
		return $updater->update($values, $entity);
	}

	/**
	 * @param ArticleEntity|mixed $entity
	 *
	 * @return bool
	 */
	public function delete($entity)
	{
		// Get entity deleter
		$deleter = $this->entityCrud->getEntityDeleter();

		// Assign before delete entity events
		$deleter->beforeAction[] = function (ArticleEntity $entity) {
		};

		// Assign after delete entity events
		$deleter->afterAction[] = function () {
		};

		// Delete entity from database
		return $deleter->delete($entity);
	}
}
```

and that is all, now just register this manager as service:

```neon
services:
	yourEntityManager:
		class: Your\Cool\NameSpace\YourEntityManager(@yourEntityCrud)
```

### Creating new entity

For example you need create new entity after form submitting. Inject created manager service and just pass values to create method:

```php
class ItemPresenter extends \Nette\Application\UI\Presenter
{
    /**
     * @var Your\Cool\NameSpace\YourEntityManager
     */
    public $manager;

    public function actionCreate()
    {
        $form = new \Nette\Application\UI\Form;
        // ...
        
        $form->onSuccess[] = (function($form, \Nette\Utils\ArrayHash $values){
            // ...
            $entity = $this->manager->create($values);
            // ...
        });
    }
}
```

Don't worry about your entity constructor dependencies. This extension will take a look on constructor dependencies and passed values and tries to create entity with proper values.
So if you have entity with dependencies like this:

```php
class MyEntityWithConstructor implements \IPub\DoctrineCrud\Entities\IEntity
{
    use \IPub\DoctrineCrud\Entities\TEntity;

    // ...

    public function __construct(string $name, OwnerEntity $owner)
    {
        $this->name = $name;
        $this->owner = $owner
    }

    // ...
}
```

You have just to pass all needed values to manager:

```php
$values = new \Nette\Utils\ArrayHash();
$values->name = 'My name is';
$values->owner = $ownerEntity;

$entity = $this->manager->create($values);
```

And a dark magic inside the extension will create new instance of entity without any problems :D

### Updating existing entity

It is like creating, but you need to pass existing entity or identifier

```php
class ItemPresenter extends \Nette\Application\UI\Presenter
{
    /**
     * @var Your\Cool\NameSpace\YourEntityManager
     */
    public $manager;

    /**
     * @param int $id
     */
    public function actionUpdate($id)
    {
        $form = new \Nette\Application\UI\Form;
        // ...
        
        $form->onSuccess[] = (function($form, \Nette\Utils\ArrayHash $values){
            // ...
            $entity = $this->manager->update($id, $values);
            // ...
        });
    }

    // OR
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * @param int $id
     */
    public function actionUpdate($id)
    {
        $entity = $this->em->find($id);

        $form = new \Nette\Application\UI\Form;
        // ...
        
        $form->onSuccess[] = (function($form, \Nette\Utils\ArrayHash $values){
            // ...
            $entity = $this->manager->update($entity, $values);
            // ...
        });
    }
}
```

### Deleting existing entity

```php
class ItemPresenter extends \Nette\Application\UI\Presenter
{
    /**
     * @var Your\Cool\NameSpace\YourEntityManager
     */
    public $manager;

    public function handleDelete($id)
    {
        // ...
        $this->manager->delete($id);
        // ...
    }

    // OR
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    public function handleDelete($id)
    {
        $entity = $this->em->find($id);

        // ...
        $this->manager->delete($entity);
        // ...
    }
}
```

### Entities annotations

Every property which should be filled during creating or updating entity must have special annotation.

```php
use Doctrine\ORM\Mapping as ORM;

use IPub\DoctrineCrud\Mapping\Annotation\Crud;

/**
 * @ORM\Entity
 */
class ArticleEntity implements \IPub\DoctrineCrud\Entities\IEntity
{
	use \IPub\DoctrineCrud\Entities\TEntity;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 *
	 * @Crud(is={"required", "writable"})
	 */
	private $title;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 *
	 * @Crud(is="required")
	 */
	private $another;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 *
	 * @Crud(is="writable" validator="NameSpace\To\Your\Validator")
	 */
	private $some;
}
```

#### Annotation syntax

**@IPub\DoctrineCrud\Mapping\Annotation\Crud** used in entity property, tells that this column/property should be handled during creating or updating.

##### Available options

* **is**: main and required option which define event for triggering property check. Allowed values are: **required**, **writable**. 
* **validator**: is used for defining validators. When the tracked property is changed, given validator is triggered.

When the attribute has defined **required** event it has to be filled in input data, otherwise an exception will be thrown. **Writable** event is need for properties which could be edited.

Property without annotation will be skipped and not filled with provided value.

### Useful traits and interfaces

This extension come with two useful traits **TIdentifiedEntity**/**IIdentifiedEntity** for identified entity type and **TEntity**/**IEntity** for all other entities. One of this traits have to be implemented in your entities, because CRUD service is using them for detecting valid entity.

And why are useful? If you want to convert entity to array, eg. for forms, you can use special methods:

```php
    $entity = new SomeEntity;
    
    $simpleArray = $entity->toSimpleArray();

    $deepLevel = 1
    $array = $entity->toArray($deepLevel);
```

The first method will convert entity to simple array, and in case entity has some relations, they are converted to identifiers or collection of identifiers.

Second method will create more complex array, but you have to specify deep level, because you can have infinite relations loop, and this level is here to protect your code against this loop.

### Property validators

With custom validators you can easily check values before they are inserted into your entity.

Validators have to be registered as services with special tag:

```neon
services:
    customValidatorName:
        class: Your\NameSpace\SomeValidator
        tags: [ipub.doctrine.validator]
```

and have to implement validator interface:

```php
class SomeValidator implements \IPub\DoctrineCrud\IValidator\IValidator
{
    /**
     * @param mixed $data
     * 
     * @return bool
     */
    public function validate($data) : bool
    {
        // do validation
        if ($data !== 'expected') {
            return FALSE;
        }

        return TRUE;
    }
}
```
