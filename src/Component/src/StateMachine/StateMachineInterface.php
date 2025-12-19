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

namespace Sylius\Resource\StateMachine;

use SM\StateMachine\StateMachineInterface as BaseStateMachineInterface;
use Sylius\Resource\Exception\RuntimeException;

if (!interface_exists(BaseStateMachineInterface::class)) {
    throw new RuntimeException(sprintf('Cannot use the "%s" interface when the "winzou/state-machine" package is not installed.', StateMachineInterface::class));
}

interface StateMachineInterface extends BaseStateMachineInterface
{
    /**
     * Returns the possible transition from given state or null if no transition is possible
     */
    public function getTransitionFromState(string $fromState): ?string;

    /**
     * Returns the possible transition to the given state or null if no transition is possible
     */
    public function getTransitionToState(string $toState): ?string;
}

if (!class_exists(\Sylius\Component\Resource\StateMachine\StateMachineInterface::class, false)) {
    class_alias(StateMachineInterface::class, \Sylius\Component\Resource\StateMachine\StateMachineInterface::class);
}
