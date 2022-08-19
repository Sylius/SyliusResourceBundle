<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\ClassFinder;

use Iterator;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;

final class RecursiveClassFinder implements RecursiveClassFinderInterface
{
    public function __construct(
        private Finder $finder,
    ) {
    }

    /**
     * @param array $directories
     * @return Iterator<string, ReflectionClass>
     * @throws ReflectionException
     */
    public function getFromDirectories(array $directories): Iterator
    {
        $this->finder->files()->name('*.php')->in($directories);
        $includedFiles = [];

        foreach ($this->finder as $sourceFile) {
            try {
                require_once $sourceFile->getRealPath();
            } catch (\Throwable $t) {
                continue;
            }

            $includedFiles[$sourceFile->getRealPath()] = true;
        }

        $declaredClasses = get_declared_classes();

        foreach ($declaredClasses as $className) {
            $reflectionClass = new ReflectionClass($className);
            $sourceFile = $reflectionClass->getFileName();

            if (isset($includedFiles[$sourceFile])) {
                yield $className => $reflectionClass;
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    public function getFromDirectoriesWithAttribute(array $directories, string $attribute): Iterator
    {
        foreach ($this->getFromDirectories($directories) as $className => $reflectionClass) {
            if ($reflectionClass->getAttributes($attribute)) {
                yield $className => $reflectionClass;
            }
        }
    }
}
