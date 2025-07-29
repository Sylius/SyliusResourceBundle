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

use Sylius\Resource\Metadata\Mutator\OperationMutatorCollectionInterface;
use Sylius\Resource\Metadata\Mutator\ResourceMutatorCollectionInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class MutatorResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private readonly ResourceMutatorCollectionInterface $resourceMutators,
        private readonly OperationMutatorCollectionInterface $operationMutators,
        private readonly ?ResourceMetadataCollectionFactoryInterface $decorated = null,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceMetadataCollection = new ResourceMetadataCollection();
        if ($this->decorated) {
            $resourceMetadataCollection = $this->decorated->create($resourceClass);
        }

        $newMetadataCollection = new ResourceMetadataCollection();

        /** @var ResourceMetadata $resource */
        foreach ($resourceMetadataCollection as $resource) {
            $resource = $this->mutateResource($resource, $resourceClass);
            $operations = $this->mutateOperations($resource->getOperations() ?? new Operations());
            $resource = $resource->withOperations($operations);

            $newMetadataCollection[] = $resource;
        }

        return $newMetadataCollection;
    }

    private function mutateResource(ResourceMetadata $resource, string $resourceClass): ResourceMetadata
    {
        foreach ($this->resourceMutators->get($resourceClass) as $mutator) {
            $resource = $mutator($resource);
        }

        return $resource;
    }

    /**
     * @template T of Operation
     * @param Operations<T> $operations
     * @return Operations<T>
     */
    private function mutateOperations(Operations $operations): Operations
    {
        $newOperations = new Operations();

        foreach ($operations as $key => $operation) {
            foreach ($this->operationMutators->get($key) as $mutator) {
                $operation = $mutator($operation);
            }

            $newOperations->add($key, $operation);
        }

        return $newOperations;
    }
}
