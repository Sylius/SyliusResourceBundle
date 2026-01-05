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

final class ClassReflection
{
    /**
     * @return \Generator<class-string>
     */
    public static function getResourcesByPaths(array $paths): iterable
    {
        trigger_deprecation('sylius/resource-bundle', '1.14', 'The method "%s" is deprecated, use "%s::%s" instead.', __METHOD__, ReflectionClassRecursiveIterator::class, 'getReflectionClassesFromDirectories');

        foreach (ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories($paths) as $reflectionClass) {
            yield $reflectionClass->getName();
        }
    }

    public static function getResourcesByPath(string $path): iterable
    {
        trigger_deprecation('sylius/resource-bundle', '1.14', 'The method "%s" is deprecated, use "%s::%s" instead.', __METHOD__, ReflectionClassRecursiveIterator::class, 'getReflectionClassesFromDirectories');

        foreach (ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories([$path]) as $reflectionClass) {
            yield $reflectionClass->getName();
        }
    }

    /**
     * @param class-string $className
     *
     * @return \ReflectionAttribute[]
     */
    public static function getClassAttributes(string $className, ?string $attributeName = null): array
    {
        $reflectionClass = new \ReflectionClass($className);

        /** @psalm-suppress ArgumentTypeCoercion */
        return $reflectionClass->getAttributes($attributeName);
    }
}

if (!class_exists(\Sylius\Component\Resource\Reflection\ClassReflection::class, false)) {
    class_alias(ClassReflection::class, \Sylius\Component\Resource\Reflection\ClassReflection::class);
}
