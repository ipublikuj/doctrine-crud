<?php
/**
 * ValidatorProxy.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           06.12.15
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud\Validation;

use Doctrine\Common;

use Nette;

use IPub\DoctrineCrud\Mapping;

/**
 * Doctrine CRUD validators container
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class ValidatorProxy implements IValidator
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var IValidator[]
	 */
	private $validators = [];

	/**
	 * @var Common\Annotations\CachedReader
	 */
	private $annotationReader;

	/**
	 * @param Common\Annotations\Reader $annotationReader
	 */
	public function __construct(Common\Annotations\Reader $annotationReader)
	{
		$this->annotationReader = $annotationReader;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate($data) : bool
	{
		list($data, $field) = func_get_args() + [NULL, NULL];

		$crud = $this->annotationReader->getPropertyAnnotation($field, Mapping\Annotation\Crud::class);

		$validators = is_array($crud->validator) ? $crud->validator : [$crud->validator];

		$result = TRUE;

		foreach ($validators as $validator) {
			if ($result && isset($this->validators[$validator])) {
				$result = $this->validators[$validator]->validate($data);
			}
		}

		return $result;
	}

	/**
	 * @param IValidator $validator
	 *
	 * @return void
	 */
	public function registerValidator(IValidator $validator) : void
	{
		$this->validators[get_class($validator)] = $validator;
	}
}
