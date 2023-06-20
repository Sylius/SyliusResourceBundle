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

namespace Sylius\Component\Resource\Metadata;

/**
 * The Operation has a state machine.
 *
 * @experimental
 */
interface StateMachineAwareOperationInterface
{
    public function getStateMachineComponent(): ?string;

    public function withStateMachineComponent(?string $stateMachineComponent): self;

    public function getStateMachineTransition(): ?string;

    public function withStateMachineTransition(string $stateMachineTransition): self;

    public function getStateMachineGraph(): ?string;

    public function withStateMachineGraph(string $stateMachineGraph): self;
}
