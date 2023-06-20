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

namespace Sylius\Bundle\ResourceBundle\Controller;

use SM\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class StateMachine implements StateMachineInterface
{
    private FactoryInterface $stateMachineFactory;

    public function __construct(FactoryInterface $stateMachineFactory)
    {
        $this->stateMachineFactory = $stateMachineFactory;
    }

    public function can(RequestConfiguration $configuration, ResourceInterface $resource): bool
    {
        if (!$configuration->hasStateMachine()) {
            throw new \InvalidArgumentException('State machine must be configured to apply transition, check your routing.');
        }

        $graph = $configuration->getStateMachineGraph() ?? 'default';

        /** @var string $transition */
        $transition = $configuration->getStateMachineTransition();

        return $this->stateMachineFactory->get($resource, $graph)->can($transition);
    }

    public function apply(RequestConfiguration $configuration, ResourceInterface $resource): void
    {
        if (!$configuration->hasStateMachine()) {
            throw new \InvalidArgumentException('State machine must be configured to apply transition, check your routing.');
        }

        $graph = $configuration->getStateMachineGraph() ?? 'default';

        /** @var string $transition */
        $transition = $configuration->getStateMachineTransition();

        $this->stateMachineFactory->get($resource, $graph)->apply($transition);
    }
}
