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

use Webmozart\Assert\Assert;

/**
 * Retrieves information about a class.
 *
 * @internal
 */
trait ClassInfoTrait
{
    /**
     * Get class name of the given object.
     *
     * @return class-string
     */
    private function getObjectClass(object $object): string
    {
        return $this->getRealClassName($object::class);
    }

    /**
     * Get the real class name of a class name that could be a proxy.
     *
     * @param class-string $className
     *
     * @return class-string
     */
    private function getRealClassName(string $className): string
    {
        // __CG__: Doctrine Common Marker for Proxy (ODM < 2.0 and ORM < 3.0)
        // __PM__: Ocramius Proxy Manager (ODM >= 2.0)
        $positionCg = strrpos($className, '\\__CG__\\');
        $positionPm = strrpos($className, '\\__PM__\\');

        if (false === $positionCg && false === $positionPm) {
            return $className;
        }

        if (false !== $positionCg) {
            $unProxiedClassName = substr($className, $positionCg + 8);

            Assert::classExists($unProxiedClassName);

            return $unProxiedClassName;
        }

        $className = ltrim($className, '\\');

        $unProxiedClassName = substr(
            $className,
            8 + $positionPm,
            strrpos($className, '\\') - ($positionPm + 8),
        );

        Assert::classExists($unProxiedClassName);

        return $unProxiedClassName;
    }
}
