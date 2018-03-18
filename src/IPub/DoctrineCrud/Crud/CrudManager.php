<?php
/**
 * CrudManager.php
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

namespace IPub\DoctrineCrud\Crud;

use Doctrine\Common\Persistence;

use Nette;

use IPub\DoctrineCrud\Exceptions;

/**
 * Doctrine CRUD entities manager
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
abstract class CrudManager
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var array
	 */
	public $beforeAction = [];

	/**
	 * @var array
	 */
	public $afterAction = [];

	/**
	 * @var string
	 */
	protected $entityName;

	/**
	 * @var Persistence\ObjectRepository
	 */
	protected $entityRepository;

	/**
	 * @var Persistence\ObjectManager|NULL
	 */
	protected $entityManager;

	/**
	 * @var bool
	 */
	private $flush = TRUE;

	/**
	 * @param string $entityName
	 * @param Persistence\ManagerRegistry $managerRegistry
	 */
	public function __construct(
		string $entityName,
		Persistence\ManagerRegistry $managerRegistry
	) {
		$this->entityName = $entityName;

		$this->entityManager = $managerRegistry->getManagerForClass($entityName);
		$this->entityRepository = $this->entityManager->getRepository($entityName);
	}

	/**
	 * @param bool $flush
	 *
	 * @return void
	 */
	public function setFlush(bool $flush) : void
	{
		$this->flush = $flush;
	}

	/**
	 * @return boolean
	 */
	public function getFlush() : bool
	{
		return $this->flush;
	}

	/**
	 * @param array|mixed $hooks
	 * @param array $args
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	protected function processHooks($hooks, array $args = []) : void
	{
		if (!is_array($hooks)) {
			throw new Exceptions\InvalidStateException('Hooks configuration must be in array');
		}

		foreach ($hooks as $hook) {
			if (!is_callable($hook)) {
				throw new Exceptions\InvalidStateException('Invalid callback given.');
			}

			call_user_func_array($hook, $args);
		}
	}
}
