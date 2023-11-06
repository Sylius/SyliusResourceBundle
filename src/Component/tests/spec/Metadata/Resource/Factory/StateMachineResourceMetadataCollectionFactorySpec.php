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

namespace spec\Sylius\Resource\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\Factory\StateMachineResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class StateMachineResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($resourceRegistry, $decorated, null);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(StateMachineResourceMetadataCollectionFactory::class);
    }

    function it_sets_the_default_state_machine_component_from_settings(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
        MetadataInterface $resourceConfiguration,
    ): void {
        $this->beConstructedWith($resourceRegistry, $decorated, 'symfony');

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.book')->willReturn($resourceConfiguration);

        $resourceConfiguration->getStateMachineComponent()->willReturn(null);

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getStateMachineComponent()->shouldReturn('symfony');
    }

    function it_sets_the_default_state_machine_component_from_resource_configuration(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
        MetadataInterface $resourceConfiguration,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.book')->willReturn($resourceConfiguration);

        $resourceConfiguration->getStateMachineComponent()->willReturn('symfony');

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getStateMachineComponent()->shouldReturn('symfony');
    }

    function it_sets_the_configured_state_machine_component_from_operation(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
        MetadataInterface $resourceConfiguration,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create', stateMachineComponent: 'winzou'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.book')->willReturn($resourceConfiguration);

        $resourceConfiguration->getStateMachineComponent()->willReturn('symfony');

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getStateMachineComponent()->shouldReturn('winzou');
    }

    function it_configures_apply_state_machine_transition_processor_if_operation_has_a_transition(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
        MetadataInterface $resourceConfiguration,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create', stateMachineTransition: 'publish'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.book')->willReturn($resourceConfiguration);

        $resourceConfiguration->getStateMachineComponent()->willReturn('symfony');

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getProcessor()->shouldReturn(ApplyStateMachineTransitionProcessor::class);
    }
}
