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

namespace Sylius\Component\Resource\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\BulkOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

/**
 * @experimental
 */
final class EventDispatcherBulkAwareProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decorated,
        private OperationEventDispatcherInterface $operationEventDispatcher,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        if ($operation instanceof BulkOperationInterface && \is_iterable($data)) {
            $this->operationEventDispatcher->dispatchBulkEvent($data, $operation, $context);
        }

        return $this->decorated->process($data, $operation, $context);
    }
}
