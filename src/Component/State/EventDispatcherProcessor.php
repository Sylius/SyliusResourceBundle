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

namespace Sylius\Component\Resource\State;

use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface;
use Sylius\Resource\Context\Context;

/**
 * @experimental
 */
final class EventDispatcherProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decorated,
        private OperationEventDispatcherInterface $operationEventDispatcher,
        private OperationEventHandlerInterface $eventHandler,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $operationEvent = $this->operationEventDispatcher->dispatchPreEvent($data, $operation, $context);

        $eventResponse = $this->eventHandler->handlePreProcessEvent(
            $operationEvent,
            $context,
            $operation instanceof CreateOperationInterface ? ResourceActions::INDEX : null,
        );

        if (null !== $eventResponse) {
            return $eventResponse;
        }

        $result = $this->decorated->process($data, $operation, $context);

        $operationEvent = $this->operationEventDispatcher->dispatchPostEvent($data, $operation, $context);

        $eventResponse = $this->eventHandler->handlePostProcessEvent(
            $operationEvent,
            $context,
        );

        if (null !== $eventResponse) {
            return $eventResponse;
        }

        return $result;
    }
}
