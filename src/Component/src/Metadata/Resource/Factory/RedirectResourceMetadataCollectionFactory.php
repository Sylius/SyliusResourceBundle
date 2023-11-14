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

use Sylius\Resource\Metadata\BulkOperationInterface;
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\UpdateOperationInterface;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteNameFactory;

final class RedirectResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private OperationRouteNameFactory $operationRouteNameFactory,
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
                if (!$operation instanceof HttpOperation) {
                    continue;
                }

                /** @var string $key */
                $key = $operation->getName();

                $operations->add($key, $this->addDefaults($resource, $operation));
            }

            $resource = $resource->withOperations($operations);

            $resourceCollectionMetadata[$i] = $resource;
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(ResourceMetadata $resource, HttpOperation $operation): Operation
    {
        if (null !== $operation->getRedirectToRoute()) {
            return $operation;
        }

        if ($operation instanceof BulkOperationInterface) {
            $newOperation = $this->setRedirectIfRouteExists($resource, $operation, 'index');

            if (null !== $newOperation) {
                return $newOperation;
            }
        }

        if (
            $operation instanceof CreateOperationInterface ||
            $operation instanceof UpdateOperationInterface
        ) {
            $newOperation = $this->setRedirectIfRouteExists($resource, $operation, 'show');

            if (null !== $newOperation) {
                return $newOperation;
            }

            $newOperation = $this->setRedirectIfRouteExists($resource, $operation, 'index');

            if (null !== $newOperation) {
                return $newOperation;
            }
        }

        if ($operation instanceof DeleteOperationInterface) {
            $newOperation = $this->setRedirectIfRouteExists($resource, $operation, 'index');

            if (null !== $newOperation) {
                return $newOperation;
            }
        }

        return $operation;
    }

    private function setRedirectIfRouteExists(ResourceMetadata $resource, HttpOperation $operation, string $shortName): ?Operation
    {
        $routeName = $this->operationRouteNameFactory->createRouteName($operation, $shortName);

        if ($resource->hasOperation($routeName)) {
            return $operation->withRedirectToRoute($routeName);
        }

        return null;
    }
}
