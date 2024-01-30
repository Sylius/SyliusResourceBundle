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

namespace Sylius\Resource\Reflection;

final class CallableReflection
{
    public static function from(callable $callable): \ReflectionFunctionAbstract
    {
        if ($callable instanceof \Closure) {
            return new \ReflectionFunction($callable);
        }

        if (is_string($callable)) {
            $callableParts = explode('::', $callable);
            /** @psalm-var class-string $object */
            $object = $callableParts[0];

            return count($callableParts) > 1 ? new \ReflectionMethod($object, $callableParts[1]) : new \ReflectionFunction($callable);
        }

        if (!is_array($callable)) {
            $callable = [$callable, '__invoke'];
        }

        return new \ReflectionMethod($callable[0], $callable[1]);
    }
}
