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
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

/**
 * @experimental
 */
final class EventDispatcherProcessor implements ProcessorInterface
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
        $this->operationEventDispatcher->dispatchPreEvent($data, $operation, $context);

        $result = $this->decorated->process($data, $operation, $context);

        $this->operationEventDispatcher->dispatchPostEvent($data, $operation, $context);

        return $result;
    }
}
