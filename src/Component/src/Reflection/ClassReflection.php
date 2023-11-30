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

use Symfony\Component\Finder\Finder;

final class ClassReflection
{
    public static function getResourcesByPaths(array $paths): iterable
    {
        foreach ($paths as $resourceDirectory) {
            $resources = self::getResourcesByPath($resourceDirectory);

            foreach ($resources as $className) {
                yield $className;
            }
        }
    }

    public static function getResourcesByPath(string $path): iterable
    {
        $finder = new Finder();
        $finder->files()->in($path)->name('*.php')->sortByName(true);

        foreach ($finder as $file) {
            $fileContent = (string) file_get_contents((string) $file->getRealPath());

            preg_match('/namespace (.+);/', $fileContent, $matches);

            $namespace = $matches[1] ?? null;

            if (!preg_match('/class +([^{ ]+)/', $fileContent, $matches)) {
                // no class found
                continue;
            }

            $className = trim($matches[1]);

            if (null !== $namespace) {
                yield $namespace . '\\' . $className;
            } else {
                yield $className;
            }
        }
    }

    /**
     * @psalm-param class-string $className
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

class_alias(ClassReflection::class, \Sylius\Component\Resource\Reflection\ClassReflection::class);
