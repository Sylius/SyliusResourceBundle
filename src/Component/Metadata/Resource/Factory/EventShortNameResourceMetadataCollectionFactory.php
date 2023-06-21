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

namespace Sylius\Component\Resource\Metadata\Resource\Factory;

use Sylius\Component\Resource\Metadata\ApplyStateMachineTransition;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\ResourceActions;

final class EventShortNameResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private ResourceMetadataCollectionFactoryInterface $decorated,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceCollectionMetadata = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceCollectionMetadata->getIterator() as $i => $resource) {
            $operations = $resource->getOperations() ?? new Operations();

            /** @var Operation $operation */
            foreach ($operations as $operation) {
                /** @var string $key */
                $key = $operation->getName();

                $operations->add($key, $this->addDefaults($resource, $operation));
            }

            $resource = $resource->withOperations($operations);

            $resourceCollectionMetadata[$i] = $resource;
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(ResourceMetadata $resource, Operation $operation): Operation
    {
        if (null === $operation->getEventShortName()) {
            $shortName = $operation instanceof ApplyStateMachineTransition ? ResourceActions::UPDATE : $operation->getShortName() ?? '';

            $bulkPrefix = 'bulk_';

            if (\str_starts_with($shortName, $bulkPrefix)) {
                $shortName = substr($shortName, strlen($bulkPrefix));
            }

            $operation = $operation->withEventShortName($shortName);
        }

        return $operation;
    }
}
