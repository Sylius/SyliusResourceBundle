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

use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;

final class StateMachineResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private ResourceMetadataCollectionFactoryInterface $decorated,
        private ?string $defaultStateMachineComponent,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceCollectionMetadata = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceCollectionMetadata->getIterator() as $i => $resource) {
            $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias() ?? '');
            $operations = $resource->getOperations() ?? new Operations();

            /** @var Operation $operation */
            foreach ($operations as $operation) {
                /** @var string $key */
                $key = $operation->getName();

                $operations->add($key, $this->addDefaults($resourceConfiguration, $operation));
            }

            $resource = $resource->withOperations($operations);

            $resourceCollectionMetadata[$i] = $resource;
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(MetadataInterface $resourceConfiguration, Operation $operation): Operation
    {
        if (!$operation instanceof StateMachineAwareOperationInterface) {
            return $operation;
        }

        if (
            null === $operation->getStateMachineComponent() &&
            method_exists($resourceConfiguration, 'getStateMachineComponent')
        ) {
            $stateMachineComponent = $resourceConfiguration->getStateMachineComponent() ?? $this->defaultStateMachineComponent;

            /** @var Operation $operation */
            $operation = $operation->withStateMachineComponent($stateMachineComponent);
        }

        if (
            method_exists($operation, 'getStateMachineTransition') &&
            null !== $operation->getStateMachineTransition() &&
            null === $operation->getProcessor()
        ) {
            $operation = $operation->withProcessor(ApplyStateMachineTransitionProcessor::class);
        }

        return $operation;
    }
}
