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

use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\FactoryAwareOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class FactoryResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private ResourceMetadataCollectionFactoryInterface $decorated,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceCollectionMetadata = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceCollectionMetadata->getIterator() as $i => $resource) {
            $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias() ?? '');
            $operations = $resource->getOperations() ?? new Operations();

            /** @var Operation|(Operation&FactoryAwareOperationInterface) $operation */
            foreach ($operations as $operation) {
                if (!$operation instanceof FactoryAwareOperationInterface) {
                    continue;
                }

                /** @var string $key */
                $key = $operation->getName();

                /** @var Operation&FactoryAwareOperationInterface $operation */
                $operation = $this->addDefaults($resourceConfiguration, $resource, $operation);

                $operations->add($key, $operation);
            }

            $resource = $resource->withOperations($operations);

            $resourceCollectionMetadata[$i] = $resource;
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(MetadataInterface $resourceConfiguration, ResourceMetadata $resource, FactoryAwareOperationInterface $operation): FactoryAwareOperationInterface
    {
        if (null === $operation->getFactory() && str_starts_with($resourceConfiguration->getDriver() ?: '', 'doctrine')) {
            $operation = $operation->withFactory($resourceConfiguration->getServiceId('factory'));
        }

        if (null === $operation->getFactoryMethod()) {
            $operation = $operation->withFactoryMethod('createNew');
        }

        return $operation;
    }
}
