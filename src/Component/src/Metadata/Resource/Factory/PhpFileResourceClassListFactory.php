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

use Sylius\Resource\Metadata\Extractor\ResourceExtractorInterface;
use Sylius\Resource\Metadata\Resource\ResourceClassList;

/**
 * Creates a resource class list from PHP configuration files.
 *
 * @experimental
 */
final class PhpFileResourceClassListFactory implements ResourceClassListFactoryInterface
{
    public function __construct(
        private readonly ResourceExtractorInterface $phpFileResourceMetadataExtractor,
        private readonly ?ResourceClassListFactoryInterface $decorated = null,
    ) {
    }

    public function create(): ResourceClassList
    {
        $classes = [];

        if ($this->decorated) {
            foreach ($this->decorated->create() as $resourceClass) {
                $classes[$resourceClass] = true;
            }
        }

        foreach ($this->phpFileResourceMetadataExtractor->getResources() as $resource) {
            $resourceClass = $resource->getClass();

            if (null === $resourceClass) {
                continue;
            }

            $classes[$resourceClass] = true;
        }

        return new ResourceClassList(array_keys($classes));
    }
}
