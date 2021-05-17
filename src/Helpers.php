<?php declare(strict_types = 1);

/**
 * Helpers.php
 *
 * @copyright      More in LICENSE.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud;

use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Doctrine CRUD helpers
 *
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Helpers
{

	/**
	 * This method was inspired by same method in Nette framework
	 *
	 * @param ReflectionMethod $method
	 * @param mixed[] $arguments
	 *
	 * @return mixed[]
	 *
	 * @throws Exceptions\EntityCreationException
	 *
	 * @throws ReflectionException
	 */
	public static function autowireArguments(ReflectionMethod $method, array $arguments): array
	{
		$optCount = 0;
		$num = -1;
		$res = [];
		$methodName = $method->getDeclaringClass()->getName() . '::' . $method->getName() . '()';

		foreach ($method->getParameters() as $subNum => $parameter) {
			if (!$parameter->isVariadic() && array_key_exists($parameter->getName(), $arguments)) {
				$res[$subNum] = $arguments[$parameter->getName()];
				unset($arguments[$parameter->getName()], $arguments[$subNum]);
				$optCount = 0;

			} elseif (array_key_exists($subNum, $arguments)) {
				$res[$subNum] = $arguments[$subNum];
				unset($arguments[$subNum]);
				$optCount = 0;

			} else {
				$class = self::getParameterType($parameter);

				if (($class !== null && $parameter->allowsNull()) || $parameter->isOptional() || $parameter->isDefaultValueAvailable()) {
					// !optional + defaultAvailable = func($a = null, $b) since 5.4.7
					// optional + !defaultAvailable = i.e. Exception::__construct, mysqli::mysqli, ...
					$res[$subNum] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
					$optCount++;

				} else {
					if ($class !== null && class_exists($class)) {
						foreach ($arguments as $key => $argument) {
							/** @phpstan-ignore-next-line */
							if (is_object($argument) && is_subclass_of($argument, $class)) {
								$res[$subNum] = $argument;
								unset($arguments[$key]);
								$optCount = 0;

								continue 2;
							}
						}
					}

					throw new Exceptions\EntityCreationException($parameter->getName(), sprintf('Parameter %s in $methodName has no class type hint or default value, so its value must be specified.', $parameter->getName()));
				}
			}
		}

		// extra parameters
		while (array_key_exists(++$num, $arguments)) {
			$res[$num] = $arguments[$num];
			unset($arguments[$num]);
			$optCount = 0;
		}

		return $optCount > 0 ? array_slice($res, 0, -$optCount) : $res;
	}

	/**
	 * @param ReflectionParameter $param
	 *
	 * @return string|null
	 */
	public static function getParameterType(ReflectionParameter $param): ?string
	{
		if ($param->hasType()) {
			$rt = $param->getType();

			$type = $rt !== null && method_exists($rt, 'getName') ? $rt->getName() : null;

			$rc = $param->getDeclaringClass();

			if ($rc === null) {
				return null;
			}

			return $type !== null && strtolower($type) === 'self' ? $rc->getName() : $type;
		}

		return null;
	}

}
