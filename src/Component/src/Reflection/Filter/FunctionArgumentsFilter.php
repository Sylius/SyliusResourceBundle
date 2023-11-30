<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Resource\Reflection\Filter;

final class FunctionArgumentsFilter
{
    public static function filter(\ReflectionFunctionAbstract $reflectionFunction, array $arguments): array
    {
        $repositoryArguments = self::getFunctionArguments($reflectionFunction);

        $allowed = array_intersect_key($repositoryArguments, $arguments);

        return array_intersect_key($arguments, array_flip(array_keys($allowed)));
    }

    private static function getFunctionArguments(\ReflectionFunctionAbstract $reflectionFunction): array
    {
        $arguments = [];

        foreach ($reflectionFunction->getParameters() as $param) {
            $arguments[$param->name] = null;
        }

        return $arguments;
    }
}
