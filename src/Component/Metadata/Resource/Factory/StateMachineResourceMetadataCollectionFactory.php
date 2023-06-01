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

use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Component\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;

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
        if (
            $operation instanceof StateMachineAwareOperationInterface &&
            null === $operation->getStateMachineComponent() &&
            method_exists($resourceConfiguration, 'getStateMachineComponent')
        ) {
            $stateMachineComponent = $resourceConfiguration->getStateMachineComponent() ?? $this->defaultStateMachineComponent;

            /** @var Operation $operation */
            $operation = $operation->withStateMachineComponent($stateMachineComponent);

            if (
                null !== $operation->getStateMachineTransition() &&
                null === $operation->getProcessor()
            ) {
                $operation = $operation->withProcessor(ApplyStateMachineTransitionProcessor::class);
            }
        }

        return $operation;
    }
}
