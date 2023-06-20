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
    public function __construct(private ?Registry $registry = null)
    {
    }

    public function can(object $data, Operation $operation, Context $context): bool
    {
        $transition = $operation->getStateMachineTransition() ?? null;

        Assert::notNull($transition, sprintf('No State machine transition was found on operation "%s".', $operation->getName() ?? ''));

        $graph = $operation->getStateMachineGraph() ?? null;

        return $this->getRegistry()->get($data, $graph)->can($data, $transition);
    }

    public function apply(object $data, Operation $operation, Context $context): void
    {
        $transition = $operation->getStateMachineTransition() ?? null;

        Assert::notNull($transition, sprintf('No State machine transition was found on operation "%s".', $operation->getName() ?? ''));

        $graph = $operation->getStateMachineGraph() ?? null;

        $this->getRegistry()->get($data, $graph)->apply($data, $transition);
    }

    private function getRegistry(): Registry
    {
        if (null === $this->registry) {
            throw new \LogicException('You can not use the "state-machine" if Symfony workflow is not available. Try running "composer require symfony/workflow".');
        }

        return $this->registry;
    }
}
