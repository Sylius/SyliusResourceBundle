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

namespace Sylius\Component\Resource\Winzou\StateMachine;

use SM\Factory\FactoryInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\StateMachine\OperationStateMachineInterface;

final class OperationStateMachine implements OperationStateMachineInterface
{
    public function __construct(private FactoryInterface $stateMachineFactory)
    {
    }

    public function can(object $data, Operation $operation, Context $context): bool
    {
        if (null === $operation->getStateMachine()) {
            throw new \InvalidArgumentException('State machine must be configured to apply transition, check your routing.');
        }

        $graph = $operation->getStateMachine()['graph'] ?? 'default';

        $transition = $operation->getStateMachine()['transition'] ?? null;

        if (null === $transition) {
            throw new \RuntimeException('Transition must be configured.');
        }

        return $this->stateMachineFactory->get($data, $graph)->can($transition);
    }

    public function apply(object $data, Operation $operation, Context $context): void
    {
        if (null === $operation->getStateMachine()) {
            throw new \InvalidArgumentException('State machine must be configured to apply transition, check your routing.');
        }

        $graph = $operation->getStateMachine()['graph'] ?? 'default';

        $transition = $operation->getStateMachine()['transition'] ?? null;

        if (null === $transition) {
            throw new \RuntimeException('Transition must be configured.');
        }

        $this->stateMachineFactory->get($data, $graph)->apply($transition);
    }
}
