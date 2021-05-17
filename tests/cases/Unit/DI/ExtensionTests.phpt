<?php declare(strict_types = 1);

namespace Tests\Cases;

use IPub\DoctrineCrud\Crud;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../BaseTestCase.php';

/**
 * @testCase
 */
final class ExtensionTests extends BaseTestCase
{

	public function testFunctional(): void
	{
		$dic = $this->createContainer();

		$crudFactory = $dic->getByType(Crud\IEntityCrudFactory::class);

		Assert::true($crudFactory instanceof Crud\IEntityCrudFactory);
	}

}

$test_case = new ExtensionTests();
$test_case->run();
