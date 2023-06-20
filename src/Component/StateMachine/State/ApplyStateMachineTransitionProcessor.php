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

namespace Sylius\Component\Resource\StateMachine\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\StateMachine\OperationStateMachineInterface;

final class ApplyStateMachineTransitionProcessor implements ProcessorInterface
{
    public function __construct(
        private OperationStateMachineInterface $stateMachine,
        private ?ProcessorInterface $writeProcessor = null,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        if (\is_object($data) && $this->stateMachine->can($data, $operation, $context)) {
            $this->stateMachine->apply($data, $operation, $context);
        }

        return $this->writeProcessor?->process($data, $operation, $context);
    }
}
