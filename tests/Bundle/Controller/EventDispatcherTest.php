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

use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcher;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface as ControllerEventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\ResourceActions;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherTest extends TestCase
{
    private MockObject $eventDispatcherMock;

    private EventDispatcher $eventDispatcher;

    protected function setUp(): void
    {
        $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $this->eventDispatcher = new EventDispatcher($this->eventDispatcherMock);
    }

    public function testImplementsEventDispatcherInterface(): void
    {
        $this->assertInstanceOf(ControllerEventDispatcherInterface::class, $this->eventDispatcher);
    }

    public function testDispatchesAppropriateEventForAResource(): void
    {
        $requestConfiguration = $this->createRequestConfiguration(null);
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectEventDispatched('sylius.product.show');

        $result = $this->eventDispatcher->dispatch(ResourceActions::SHOW, $requestConfiguration, $resource);

        $this->assertInstanceOf(ResourceControllerEvent::class, $result);
    }

    public function testDispatchesAppropriateCustomEventForAResource(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('register');
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectEventDispatched('sylius.product.register');

        $result = $this->eventDispatcher->dispatch(ResourceActions::CREATE, $requestConfiguration, $resource);

        $this->assertInstanceOf(ResourceControllerEvent::class, $result);
    }

    public function testDispatchesEventForACollectionOfResources(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('register');
        $resources = $this->createMock(Collection::class);

        $this->expectEventDispatched('sylius.product.register');

        $result = $this->eventDispatcher->dispatchMultiple(ResourceActions::CREATE, $requestConfiguration, $resources);

        $this->assertInstanceOf(ResourceControllerEvent::class, $result);
    }

    public function testDispatchesAppropriatePreEventForAResource(): void
    {
        $requestConfiguration = $this->createRequestConfiguration(null);
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectEventDispatched('sylius.product.pre_create');

        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $requestConfiguration, $resource);
    }

    public function testDispatchesAppropriateCustomPreEventForAResource(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('register');
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectEventDispatched('sylius.product.pre_register');

        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $requestConfiguration, $resource);
    }

    public function testDispatchesAppropriatePostEventForAResource(): void
    {
        $requestConfiguration = $this->createRequestConfiguration(null);
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectEventDispatched('sylius.product.post_create');

        $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $requestConfiguration, $resource);
    }

    public function testDispatchesAppropriateCustomPostEventForAResource(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('register');
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectEventDispatched('sylius.product.post_register');

        $result = $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $requestConfiguration, $resource);

        $this->assertInstanceOf(ResourceControllerEvent::class, $result);
    }

    /**
     * @return MockObject&RequestConfiguration
     */
    private function createRequestConfiguration(?string $customEvent): MockObject
    {
        /** @var MockObject&MetadataInterface $metadata */
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata->method('getApplicationName')->willReturn('sylius');
        $metadata->method('getName')->willReturn('product');

        /** @var MockObject&RequestConfiguration $requestConfiguration */
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $requestConfiguration->method('getEvent')->willReturn($customEvent);
        $requestConfiguration->method('getMetadata')->willReturn($metadata);

        return $requestConfiguration;
    }

    private function expectEventDispatched(string $eventName): void
    {
        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->isInstanceOf(ResourceControllerEvent::class),
                $eventName,
            );
    }
}
