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

namespace Sylius\Resource\State\Processor;

use Sylius\Component\Resource\ResourceActions;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface;

/**
 * @experimental
 */
final class PreEventDispatcherProcessor implements ProcessorInterface
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
        $operationEvent = $this->operationEventDispatcher->dispatchPreEvent($data, $operation, $context);

        $eventResponse = $this->eventHandler->handlePreProcessEvent(
            $operationEvent,
            $context,
            $operation instanceof CreateOperationInterface ? ResourceActions::INDEX : null,
        );

        if (null !== $eventResponse) {
            return $eventResponse;
        }

        return $this->processor->process($data, $operation, $context);
    }
}
