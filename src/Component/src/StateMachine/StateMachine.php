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

use SM\StateMachine\StateMachine as BaseStateMachine;
use Sylius\Resource\Exception\RuntimeException;

if (!class_exists(BaseStateMachine::class)) {
    throw new RuntimeException(sprintf('Cannot use the "%s" class when the "winzou/state-machine" package is not installed.', StateMachine::class));
}

final class StateMachine extends BaseStateMachine implements StateMachineInterface
{
    public function getTransitionFromState(string $fromState): ?string
    {
        foreach ($this->getPossibleTransitions() as $transition) {
            $config = $this->config['transitions'][$transition];
            if (in_array($fromState, $config['from'], true)) {
                return $transition;
            }
        }

        return null;
    }

    public function getTransitionToState(string $toState): ?string
    {
        foreach ($this->getPossibleTransitions() as $transition) {
            $config = $this->config['transitions'][$transition];
            if ($toState === $config['to']) {
                return $transition;
            }
        }

        return null;
    }
}

if (!class_exists(\Sylius\Component\Resource\StateMachine\StateMachine::class, false)) {
    class_alias(StateMachine::class, \Sylius\Component\Resource\StateMachine\StateMachine::class);
}
