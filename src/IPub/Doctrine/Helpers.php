<?php
/**
 * Helpers.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Doctrine!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           29.01.14
 */

declare(strict_types = 1);

namespace IPub\Doctrine;

use Nette;

use IPub;
use IPub\Doctrine\Exceptions;

class Helpers
{
	/**
	 * @param \ReflectionMethod $method
	 * @param array $arguments
	 *
	 * @return array
	 *
	 * @throws Exceptions\EntityCreationException
	 * @throws \ReflectionException
	 */
	public static function autowireArguments(\ReflectionMethod $method, array $arguments)
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
	public static function getParameterType(\ReflectionParameter $param)
	{
		if ($param->hasType()) {
			$type = PHP_VERSION_ID >= 70100 ? $param->getType()->getName() : (string) $param->getType();
			return strtolower($type) === 'self' ? $param->getDeclaringClass()->getName() : $type;
		}

		return NULL;
	}
}
