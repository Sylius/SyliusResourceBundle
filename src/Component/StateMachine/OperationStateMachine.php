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

namespace Sylius\Component\Resource\StateMachine;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\StateMachineAwareOperationInterface;
use Webmozart\Assert\Assert;

final class OperationStateMachine implements OperationStateMachineInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    public function can(object $data, Operation $operation, Context $context): bool
    {
        $stateMachine = $this->getStateMachine($operation);

        return $stateMachine?->can($data, $operation, $context) ?? false;
    }

    public function apply(object $data, Operation $operation, Context $context): void
    {
        $stateMachine = $this->getStateMachine($operation);

        $stateMachine?->apply($data, $operation, $context);
    }

    private function getStateMachine(Operation $operation): ?OperationStateMachineInterface
    {
        Assert::isInstanceOf($operation, StateMachineAwareOperationInterface::class);
        $stateMachine = $operation->getStateMachineComponent();

        if (null === $stateMachine) {
            return null;
        }

        if (!$this->locator->has($stateMachine)) {
            throw new \RuntimeException(sprintf('State machine "%s" not found on operation "%s"', $stateMachine, $operation->getName() ?? ''));
        }

        $stateMachineInstance = $this->locator->get($stateMachine);
        Assert::isInstanceOf($stateMachineInstance, OperationStateMachineInterface::class);

        return $stateMachineInstance;
    }
}
