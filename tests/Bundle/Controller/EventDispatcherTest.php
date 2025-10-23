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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcher;
use Doctrine\Common\Collections\Collection;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface as ControllerEventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\ResourceActions;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherTest extends TestCase
{
    /**
     * @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface|MockObject
     */
    private MockObject $eventDispatcherMock;
    private EventDispatcher $eventDispatcher;
    protected function setUp(): void
    {
        $this->eventDispatcherMock = $this->createMock(\Symfony\Contracts\EventDispatcher\EventDispatcherInterface::class);
        $this->eventDispatcher = new EventDispatcher($this->eventDispatcherMock);
    }

    function testImplementsEventDispatcherInterface(): void
    {
        $this->assertInstanceOf(ControllerEventDispatcherInterface::class, $this->eventDispatcher);
    }

    function testDispatchesAppropriateEventForAResource(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getEvent')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ResourceControllerEvent::class), 'sylius.product.show');
        $this->assertInstanceOf(ResourceControllerEvent::class, $this->eventDispatcher->dispatch(ResourceActions::SHOW, $requestConfigurationMock, $resourceMock));
    }

    function testDispatchesAppropriateCustomEventForAResource(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getEvent')->willReturn('register');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ResourceControllerEvent::class), 'sylius.product.register');
        $this->assertInstanceOf(ResourceControllerEvent::class, $this->eventDispatcher->dispatch(ResourceActions::CREATE, $requestConfigurationMock, $resourceMock));
    }

    function testDispatchesEventForACollectionOfResources(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Collection|MockObject $resourcesMock */
        $resourcesMock = $this->createMock(Collection::class);
        $requestConfigurationMock->expects($this->once())->method('getEvent')->willReturn('register');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ResourceControllerEvent::class), 'sylius.product.register');
        $this->assertInstanceOf(ResourceControllerEvent::class, $this->eventDispatcher->dispatchMultiple(ResourceActions::CREATE, $requestConfigurationMock, $resourcesMock));
    }

    function testDispatchesAppropriatePreEventForAResource(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getEvent')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ResourceControllerEvent::class), 'sylius.product.pre_create');
        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $requestConfigurationMock, $resourceMock);
    }

    function testDispatchesAppropriateCustomPreEventForAResource(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getEvent')->willReturn('register');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ResourceControllerEvent::class), 'sylius.product.pre_register');
        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $requestConfigurationMock, $resourceMock);
    }

    function testDispatchesAppropriatePostEventForAResource(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getEvent')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ResourceControllerEvent::class), 'sylius.product.post_create');
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $requestConfigurationMock, $resourceMock);
    }

    function testDispatchesAppropriateCustomPostEventForAResource(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getEvent')->willReturn('register');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with($this->isInstanceOf(ResourceControllerEvent::class), 'sylius.product.post_register');
        $this->assertInstanceOf(ResourceControllerEvent::class, $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $requestConfigurationMock, $resourceMock));
    }
}
