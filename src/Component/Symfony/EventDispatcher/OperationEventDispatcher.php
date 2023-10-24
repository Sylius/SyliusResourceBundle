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

namespace Sylius\Component\Resource\Symfony\EventDispatcher;

use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Resource\Context\Context;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class OperationEventDispatcher implements OperationEventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(mixed $data, Operation $operation, Context $context): OperationEvent
    {
        return $this->dispatchEvent($data, $operation, $context);
    }

    public function dispatchBulkEvent(mixed $data, Operation $operation, Context $context): OperationEvent
    {
        return $this->dispatchEvent($data, $operation, $context, 'bulk');
    }

    public function dispatchPreEvent(mixed $data, Operation $operation, Context $context): OperationEvent
    {
        return $this->dispatchEvent($data, $operation, $context, 'pre');
    }

    public function dispatchPostEvent(mixed $data, Operation $operation, Context $context): OperationEvent
    {
        return $this->dispatchEvent($data, $operation, $context, 'post');
    }

    public function dispatchInitializeEvent(mixed $data, Operation $operation, Context $context): OperationEvent
    {
        return $this->dispatchEvent($data, $operation, $context, 'initialize');
    }

    private function dispatchEvent(mixed $data, Operation $operation, Context $context, ?string $eventType = null): OperationEvent
    {
        $operationEvent = new OperationEvent($data, ['operation' => $operation, 'context' => $context]);

        $resource = $operation->getResource();

        if (null === $resource) {
            return $operationEvent;
        }

        $eventName = sprintf(
            '%s.%s.%s%s',
            $resource->getApplicationName() ?? '',
            $resource->getName() ?? '',
            $eventType ? $eventType . '_' : '',
            $operation->getEventShortName() ?? '',
        );

        $this->eventDispatcher->dispatch($operationEvent, $eventName);

        return $operationEvent;
    }
}
