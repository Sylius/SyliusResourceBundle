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

use Sylius\Resource\Metadata\Extractor\MetadataExtractorInterface;
use Sylius\Resource\Metadata\Resource\ResourceClassList;

final class PhpFileResourceClassListFactory implements ResourceClassListFactoryInterface
{
    use OperationDefaultsTrait;

    public function __construct(
        private readonly MetadataExtractorInterface $metadataExtractor,
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

        foreach ($this->metadataExtractor->extract() as $resource) {
            $resourceClass = $resource->getClass();

            if (null === $resourceClass) {
                continue;
            }

            $classes[$resourceClass] = true;
        }

        return new ResourceClassList(array_keys($classes));
    }
}
