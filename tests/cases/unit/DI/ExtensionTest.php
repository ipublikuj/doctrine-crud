<?php declare(strict_types = 1);

namespace IPub\DoctrineCrud\Tests\Cases\Unit\DI;

use IPub\DoctrineCrud\Crud;
use IPub\DoctrineCrud\Tests\Cases\Unit\BaseTestCase;
use Nette\DI;

final class ExtensionTest extends BaseTestCase
{

	/**
	 * @throws DI\MissingServiceException
	 */
	public function testFunctional(): void
	{
		$dic = $this->createContainer();

		self::assertNotNull($dic->getByType(Crud\IEntityCrudFactory::class, false));
	}

}
