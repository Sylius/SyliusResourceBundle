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

namespace Sylius\Component\Resource\Symfony\Workflow;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\StateMachine\OperationStateMachineInterface;
use Symfony\Component\Workflow\Registry;
use Webmozart\Assert\Assert;

final class OperationStateMachine implements OperationStateMachineInterface
{
    public function __construct(private Registry $registry)
    {
    }

    /**
     * @inheritdoc
     */
    public function can(object $data, Operation $operation, Context $context): bool
    {
        Assert::notNull($operation->getStateMachine(), 'State machine must be configured to apply transition, check your routing.');

        $graph = $operation->getStateMachine()['graph'] ?? null;

        $transition = $operation->getStateMachine()['transition'] ?? null;

        if (null === $transition) {
            throw new \RuntimeException('Transition must be configured.');
        }

        return $this->registry->get($data, $graph)->can($data, $transition);
    }

    /**
     * @inheritdoc
     */
    public function apply(object $data, Operation $operation, Context $context): void
    {
        Assert::notNull($operation->getStateMachine(), 'State machine must be configured to apply transition, check your routing.');

        $graph = $operation->getStateMachine()['graph'] ?? null;

        $transition = $operation->getStateMachine()['transition'];

        if (null === $transition) {
            throw new \RuntimeException('Transition must be configured.');
        }

        $this->registry->get($data, $graph)->apply($data, $transition);
    }
}
