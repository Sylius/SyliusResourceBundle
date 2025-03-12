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

use Sylius\Resource\Grid\State\RequestGridProvider;
use Sylius\Resource\Metadata\GridAwareOperationInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Console\Operation\ConsoleOperation;
use Sylius\Resource\Symfony\Console\State\ConsoleGridProvider;

final class ProviderResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private ResourceMetadataCollectionFactoryInterface $decorated,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceMetadataCollection = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceMetadataCollection->getIterator() as $i => $resource) {
            $operations = $resource->getOperations() ?? new Operations();

            /** @var Operation $operation */
            foreach ($operations as $operation) {
                /** @var string $key */
                $key = $operation->getName();

                $operations->add($key, $this->addDefaults($operation));
            }

            $resource = $resource->withOperations($operations);

            $resourceMetadataCollection[$i] = $resource;
        }

        return $resourceMetadataCollection;
    }

    private function addDefaults(Operation $operation): Operation
    {
        if (
            null !== $operation->getProvider() ||
            !$operation instanceof GridAwareOperationInterface ||
            null === $operation->getGrid()
        ) {
            return $operation;
        }

        if ($operation instanceof HttpOperation) {
            $operation = $operation->withProvider(RequestGridProvider::class);
        }

        if ($operation instanceof ConsoleOperation) {
            $operation = $operation->withProvider(ConsoleGridProvider::class);
        }

        return $operation;
    }
}
