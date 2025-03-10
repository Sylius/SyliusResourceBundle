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

namespace Sylius\Resource\Metadata\Operation;

use Sylius\Resource\Metadata\Operation;
use Symfony\Component\Finder\Finder;

final class CustomPhpFileOperationUpdater implements OperationUpdaterInterface
{
    public function __construct(
        private readonly array $resourceMapping,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function update(Operation $operation): Operation
    {
        foreach ($this->getResourceFilePaths() as $filePath) {
            if (!is_readable($filePath)) {
                continue;
            }

            $resource = $this->getPHPFileClosure($filePath)();

            if (!$resource instanceof \Closure) {
                continue;
            }

            $resourceReflection = new \ReflectionFunction($resource);

            if (1 !== $resourceReflection->getNumberOfParameters()) {
                continue;
            }

            $firstParameterType = ($resourceReflection->getParameters()[0] ?? null)?->getType();

            if (!$firstParameterType instanceof \ReflectionNamedType) {
                continue;
            }

            // Check if the closure parameter is an operation
            if (!is_a($firstParameterType->getName(), Operation::class, true)) {
                continue;
            }

            $operation = $resource($operation);
        }

        return $operation;
    }

    private function getResourceFilePaths(): iterable
    {
        foreach ($this->createFinder() as $file) {
            yield $file->getPathname();
        }
    }

    private function createFinder(): Finder
    {
        $finder = (new Finder())->files();

        foreach ($this->resourceMapping['imports'] ?? [] as $path) {
            $finder->in($path);
        }

        return $finder->files();
    }

    /**
     * Scope isolated include.
     *
     * Prevents access to $this/self from included files.
     */
    private function getPHPFileClosure(string $filePath): \Closure
    {
        return \Closure::bind(function () use ($filePath): mixed {
            return require $filePath;
        }, null, null);
    }
}
