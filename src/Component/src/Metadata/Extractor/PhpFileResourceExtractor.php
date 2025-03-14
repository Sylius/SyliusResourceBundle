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

namespace Sylius\Resource\Metadata\Extractor;

use Sylius\Resource\Metadata\ResourceMetadata;

final class PhpFileResourceExtractor extends AbstractResourceExtractor
{
    /**
     * @inheritdoc
     */
    protected function extractPath(string $path): void
    {
        $resource = $this->getPHPFileClosure($path)();

        if (!$resource instanceof ResourceMetadata) {
            return;
        }

        $resourceReflection = new \ReflectionClass($resource);

        foreach ($resourceReflection->getProperties() as $property) {
            $property->setAccessible(true);
            $resolvedValue = $this->resolve($property->getValue($resource));
            $property->setValue($resource, $resolvedValue);
        }

        $this->resources = [$resource];
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
