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

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface as ControllerEventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher): void
    {
        $this->beConstructedWith($eventDispatcher);
    }

    function it_implements_event_dispatcher_interface(): void
    {
        $this->shouldImplement(ControllerEventDispatcherInterface::class);
    }

    function it_dispatches_appropriate_event_for_a_resource(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        ResourceInterface $resource,
    ): void {
        $requestConfiguration->getEvent()->willReturn(null);
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch(Argument::type(ResourceControllerEvent::class), 'sylius.product.show')->shouldBeCalled();

        $this->dispatch(ResourceActions::SHOW, $requestConfiguration, $resource)->shouldHaveType(ResourceControllerEvent::class);
    }

    function it_dispatches_appropriate_custom_event_for_a_resource(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        ResourceInterface $resource,
    ): void {
        $requestConfiguration->getEvent()->willReturn('register');
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch(Argument::type(ResourceControllerEvent::class), 'sylius.product.register')->shouldBeCalled();

        $this->dispatch(ResourceActions::CREATE, $requestConfiguration, $resource)->shouldHaveType(ResourceControllerEvent::class);
    }

    function it_dispatches_event_for_a_collection_of_resources(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        Collection $resources,
    ): void {
        $requestConfiguration->getEvent()->willReturn('register');
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch(Argument::type(ResourceControllerEvent::class), 'sylius.product.register')->shouldBeCalled();

        $this->dispatchMultiple(ResourceActions::CREATE, $requestConfiguration, $resources)->shouldHaveType(ResourceControllerEvent::class);
    }

    function it_dispatches_appropriate_pre_event_for_a_resource(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        ResourceInterface $resource,
    ): void {
        $requestConfiguration->getEvent()->willReturn(null);
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch(Argument::type(ResourceControllerEvent::class), 'sylius.product.pre_create')->shouldBeCalled();

        $this->dispatchPreEvent(ResourceActions::CREATE, $requestConfiguration, $resource);
    }

    function it_dispatches_appropriate_custom_pre_event_for_a_resource(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        ResourceInterface $resource,
    ): void {
        $requestConfiguration->getEvent()->willReturn('register');
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch(Argument::type(ResourceControllerEvent::class), 'sylius.product.pre_register')->shouldBeCalled();

        $this->dispatchPreEvent(ResourceActions::CREATE, $requestConfiguration, $resource);
    }

    function it_dispatches_appropriate_post_event_for_a_resource(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        ResourceInterface $resource,
    ): void {
        $requestConfiguration->getEvent()->willReturn(null);
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch(Argument::type(ResourceControllerEvent::class), 'sylius.product.post_create')->shouldBeCalled();

        $this->dispatchPostEvent(ResourceActions::CREATE, $requestConfiguration, $resource);
    }

    function it_dispatches_appropriate_custom_post_event_for_a_resource(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        ResourceInterface $resource,
    ): void {
        $requestConfiguration->getEvent()->willReturn('register');
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch(Argument::type(ResourceControllerEvent::class), 'sylius.product.post_register')->shouldBeCalled();

        $this->dispatchPostEvent(ResourceActions::CREATE, $requestConfiguration, $resource)->shouldHaveType(ResourceControllerEvent::class);
    }
}
