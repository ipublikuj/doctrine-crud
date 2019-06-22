<?php
/**
 * Helpers.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\DoctrineCrud;

use IPub\DoctrineCrud\Exceptions;

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
	 * This method was inspired same method in Nette framework
	 * 
	 * @param \ReflectionMethod $method
	 * @param array $arguments
	 *
	 * @return array
	 *
	 * @throws Exceptions\EntityCreationException
	 */
	public static function autowireArguments(\ReflectionMethod $method, array $arguments) : array
	{
		$optCount = 0;
		$num = -1;
		$res = [];
		$methodName = $method->getDeclaringClass()->getName() . '::' . $method->getName() . '()';

		foreach ($method->getParameters() as $num => $parameter) {
			if (!$parameter->isVariadic() && array_key_exists($parameter->getName(), $arguments)) {
				$res[$num] = $arguments[$parameter->getName()];
				unset($arguments[$parameter->getName()], $arguments[$num]);
				$optCount = 0;

			} elseif (array_key_exists($num, $arguments)) {
				$res[$num] = $arguments[$num];
				unset($arguments[$num]);
				$optCount = 0;

			} elseif ((($class = self::getParameterType($parameter)) && $class && $parameter->allowsNull()) || $parameter->isOptional() || $parameter->isDefaultValueAvailable()) {
				// !optional + defaultAvailable = func($a = NULL, $b) since 5.4.7
				// optional + !defaultAvailable = i.e. Exception::__construct, mysqli::mysqli, ...
				$res[$num] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : NULL;
				$optCount++;

			} else {
				if (($class = self::getParameterType($parameter)) && $class) {
					foreach ($arguments as $key => $argument) {
						if (is_object($argument)) {
							if (is_object($argument) && is_subclass_of($argument, $class)) {
								$res[$num] = $argument;
								unset($arguments[$key]);
								$optCount = 0;

								continue 2;
							}
						}
					}
				}

				throw new Exceptions\EntityCreationException("Parameter \${$parameter->getName()} in $methodName has no class type hint or default value, so its value must be specified.");
			}
		}

		// extra parameters
		while (array_key_exists(++$num, $arguments)) {
			$res[$num] = $arguments[$num];
			unset($arguments[$num]);
			$optCount = 0;
		}

		return $optCount ? array_slice($res, 0, -$optCount) : $res;
	}

	/**
	 * @param \ReflectionParameter $param
	 *
	 * @return string|NULL
	 */
	public static function getParameterType(\ReflectionParameter $param) : ?string
	{
		if ($param->hasType()) {
			$type = PHP_VERSION_ID >= 70100 ? $param->getType()->getName() : (string) $param->getType();
			return strtolower($type) === 'self' ? $param->getDeclaringClass()->getName() : $type;
		}

		return NULL;
	}
}
