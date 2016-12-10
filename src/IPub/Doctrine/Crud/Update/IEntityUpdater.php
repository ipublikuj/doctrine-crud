<?php
/**
 * IEntityUpdater.php
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

namespace IPub\Doctrine\Crud\Update;

use IPub;
use IPub\Doctrine\Mapping;

/**
 * Doctrine CRUD entity updater factory
 *
 * @package        iPublikuj:Doctrine!
 * @subpackage     Crud
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IEntityUpdater
{
	/**
	 * @param string $entityName
	 * @param Mapping\IEntityMapper $entityMapper
	 *
	 * @return EntityUpdater
	 */
	function create(string $entityName, Mapping\IEntityMapper $entityMapper) : EntityUpdater;
}
