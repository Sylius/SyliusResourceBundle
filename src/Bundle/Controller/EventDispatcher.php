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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    private SymfonyEventDispatcherInterface $eventDispatcher;

    public function __construct(SymfonyEventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatch(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent {
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $metadata = $requestConfiguration->getMetadata();
        $event = new ResourceControllerEvent($resource);

        $this->eventDispatcher->dispatch($event, sprintf('%s.%s.%s', $metadata->getApplicationName(), $metadata->getName(), $eventName));

        return $event;
    }

    public function dispatchMultiple(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        $resources,
    ): ResourceControllerEvent {
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $metadata = $requestConfiguration->getMetadata();
        $event = new ResourceControllerEvent($resources);

        $this->eventDispatcher->dispatch($event, sprintf('%s.%s.%s', $metadata->getApplicationName(), $metadata->getName(), $eventName));

        return $event;
    }

    public function dispatchPreEvent(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent {
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $metadata = $requestConfiguration->getMetadata();
        $event = new ResourceControllerEvent($resource);

        $this->eventDispatcher->dispatch($event, sprintf('%s.%s.pre_%s', $metadata->getApplicationName(), $metadata->getName(), $eventName));

        return $event;
    }

    public function dispatchPostEvent(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent {
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $metadata = $requestConfiguration->getMetadata();
        $event = new ResourceControllerEvent($resource);

        $this->eventDispatcher->dispatch($event, sprintf('%s.%s.post_%s', $metadata->getApplicationName(), $metadata->getName(), $eventName));

        return $event;
    }

    public function dispatchInitializeEvent(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent {
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $metadata = $requestConfiguration->getMetadata();
        $event = new ResourceControllerEvent($resource);

        $this->eventDispatcher->dispatch(
            $event,
            sprintf('%s.%s.initialize_%s', $metadata->getApplicationName(), $metadata->getName(), $eventName),
        );

        return $event;
    }
}
