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

interface StateMachineInterface extends BaseStateMachineInterface
{
    /**
     * Returns the possible transition from given state
     * Returns null if no transition is possible
     */
    public function getTransitionFromState(string $fromState): ?string;

    /**
     * Returns the possible transition to the given state
     * Returns null if no transition is possible
     */
    public function getTransitionToState(string $toState): ?string;
}

class_alias(StateMachineInterface::class, \Sylius\Component\Resource\StateMachine\StateMachineInterface::class);
