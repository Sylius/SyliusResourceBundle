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
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactoryInterface;

/**
 * @experimental
 */
final class PhpFileResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    use OperationDefaultsTrait;

    public function __construct(
        private readonly RegistryInterface $resourceRegistry,
        private readonly OperationRouteNameFactoryInterface $operationRouteNameFactory,
        private readonly ResourceExtractorInterface $phpfileResourceMetadataExtractor,
        private readonly ?ResourceMetadataCollectionFactoryInterface $decorated = null,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceMetadataCollection = new ResourceMetadataCollection();
        if ($this->decorated) {
            $resourceMetadataCollection = $this->decorated->create($resourceClass);
        }

        foreach ($this->phpfileResourceMetadataExtractor->getResources() as $resource) {
            if ($resourceClass !== $resource->getClass()) {
                continue;
            }

            $resourceAlias = $resource->getAlias();

            if (null !== $resourceAlias) {
                $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias() ?? '');
            } else {
                $resourceConfiguration = $this->resourceRegistry->getByClass($resourceClass);
            }

            $resource = $this->getResourceWithDefaults($resourceClass, $resource, $resourceConfiguration);

            $operations = [];
            /** @var Operation $operation */
            foreach ($resource->getOperations() ?? new Operations() as $operation) {
                [$key, $operation] = $this->getOperationWithDefaults($operation, $resource, $this->operationRouteNameFactory, $this->resourceRegistry);
                $operations[$key] = $operation;
            }

            if ($operations) {
                $resource = $resource->withOperations(new Operations($operations));
            }

            $resourceMetadataCollection[] = $resource;
        }

        return $resourceMetadataCollection;
    }
}
