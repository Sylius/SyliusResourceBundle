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
use Sylius\Resource\Metadata\Resource\ResourceClassList;
use Sylius\Resource\Reflection\ClassReflection;

/**
 * Creates a resource class list from {@see AsResource} attributes.
 *
 * @experimental
 */
final class AttributesResourceClassListFactory implements ResourceClassListFactoryInterface
{
    /** @param array{paths: string[]} $mapping */
    public function __construct(
        private readonly array $mapping,
        private readonly ?ResourceClassListFactoryInterface $decorated = null,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function create(): ResourceClassList
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

        return new ResourceClassList(array_keys($classes));
    }
}
