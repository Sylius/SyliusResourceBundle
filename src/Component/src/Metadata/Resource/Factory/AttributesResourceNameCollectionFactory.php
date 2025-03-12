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

namespace Sylius\Resource\Metadata\Resource\Factory;

use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Resource\ResourceNameCollection;
use Sylius\Resource\Reflection\ClassReflection;

/**
 * Creates a resource name collection from {@see AsResource} attributes.
 *
 * @experimental
 */
final class AttributesResourceNameCollectionFactory implements ResourceNameCollectionFactoryInterface
{
    /**
     * @param array{paths: string[]} $mapping
     */
    public function __construct(
        private readonly array $mapping,
        private readonly ?ResourceNameCollectionFactoryInterface $decorated = null,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function create(): ResourceNameCollection
    {
        $classes = [];

        if ($this->decorated) {
            foreach ($this->decorated->create() as $resourceClass) {
                $classes[$resourceClass] = true;
            }
        }

        $paths = $this->mapping['paths'] ?? [];

        foreach (ClassReflection::getResourcesByPaths($paths) as $resourceClass) {
            if ([] === ClassReflection::getClassAttributes($resourceClass, AsResource::class)) {
                continue;
            }

            $classes[$resourceClass] = true;
        }

        return new ResourceNameCollection(array_keys($classes));
    }
}
