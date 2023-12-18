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

namespace Sylius\Component\Resource\src\Symfony\EventDispatcher\State;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface;

/**
 * @experimental
 */
final class DispatchPostWriteEventProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private OperationEventDispatcherInterface $operationEventDispatcher,
        private OperationEventHandlerInterface $eventHandler,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $data = $this->processor->process($data, $operation, $context);

        $operationEvent = $this->operationEventDispatcher->dispatchPostEvent($data, $operation, $context);

        $eventResponse = $this->eventHandler->handlePostProcessEvent(
            $operationEvent,
            $context,
        );

        if (null !== $eventResponse) {
            return $eventResponse;
        }

        return $data;
    }
}
