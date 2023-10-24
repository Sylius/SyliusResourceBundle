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

namespace Sylius\Component\Resource\Winzou\StateMachine;

use SM\Factory\Factory;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Component\Resource\StateMachine\OperationStateMachineInterface;
use Sylius\Resource\Context\Context;
use Webmozart\Assert\Assert;

final class OperationStateMachine implements OperationStateMachineInterface
{
    public function __construct(private ?Factory $factory = null)
    {
    }

    public function can(object $data, Operation $operation, Context $context): bool
    {
        Assert::isInstanceOf($operation, StateMachineAwareOperationInterface::class);
        $transition = $operation->getStateMachineTransition() ?? null;

        Assert::notNull($transition, sprintf('No State machine transition was found on operation "%s".', $operation->getName() ?? ''));

        $graph = $operation->getStateMachineGraph() ?? 'default';

        return $this->getFactory()->get($data, $graph)->can($transition);
    }

    public function apply(object $data, Operation $operation, Context $context): void
    {
        Assert::isInstanceOf($operation, StateMachineAwareOperationInterface::class);
        $transition = $operation->getStateMachineTransition() ?? null;

        Assert::notNull($transition, sprintf('No State machine transition was found on operation "%s".', $operation->getName() ?? ''));

        $graph = $operation->getStateMachineGraph() ?? 'default';

        $this->getFactory()->get($data, $graph)->apply($transition);
    }

    private function getFactory(): Factory
    {
        if (null === $this->factory) {
            throw new \LogicException('You can not use the "state-machine" if Winzou State Machine is not available. Try running "composer require winzou/state-machine-bundle".');
        }

        return $this->factory;
    }
}
