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

namespace Sylius\Resource\Doctrine\ORM\Metadata\Resource\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\GridAwareOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class DoctrineORMResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private ResourceMetadataCollectionFactoryInterface $decorated,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceCollectionMetadata = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceCollectionMetadata->getIterator() as $i => $resource) {
            $operations = $resource->getOperations() ?? new Operations();

            $entityClass = $resource->getClass();

            if (null === $entityClass) {
                continue;
            }

            /** @var Operation $operation */
            foreach ($operations as $operation) {
                /** @var string $key */
                $key = $operation->getName();

                $entityManager = $this->managerRegistry->getManagerForClass($entityClass);

                if (!$entityManager instanceof EntityManagerInterface) {
                    $operations->add($key, $operation);

                    continue;
                }

                $operations->add($key, $this->addDefaults($operation));
            }

            $resource = $resource->withOperations($operations);

            $resourceCollectionMetadata[$i] = $resource;
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(Operation $operation): Operation
    {
        $operation = $operation->withProvider($this->getProvider($operation));

        return $operation->withProcessor($this->getProcessor($operation));
    }

    private function getProvider(Operation $operation): callable|string|null
    {
        if (null !== $provider = $operation->getProvider()) {
            return $provider;
        }

        if ($operation instanceof GridAwareOperationInterface && null !== $operation->getGrid()) {
            return null;
        }

        return 'sylius.state_provider.doctrine.orm.state.provider';
    }

    private function getProcessor(Operation $operation): callable|string
    {
        if (null !== $processor = $operation->getProcessor()) {
            return $processor;
        }

        if ($operation instanceof DeleteOperationInterface) {
            return RemoveProcessor::class;
        }

        return PersistProcessor::class;
    }
}
