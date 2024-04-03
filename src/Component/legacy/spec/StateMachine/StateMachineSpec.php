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

namespace spec\Sylius\Component\Resource\StateMachine;

use App\Entity\PullRequest;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\StateMachine\StateMachine;
use Sylius\Component\Resource\StateMachine\StateMachineInterface as LegacyStateMachineInterface;
use Sylius\Resource\StateMachine\StateMachine as NewStateMachine;
use Sylius\Resource\StateMachine\StateMachineInterface;

final class StateMachineSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(new PullRequest(), [
            'graph' => 'pull_request',
            'property_path' => 'currentPlace',
            'places' => [
                'start',
                'test',
            ],
            'transitions' => [
                'submit' => [
                    'from' => ['start'],
                    'to' => 'test',
                ],
            ],
        ]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(StateMachine::class);
    }

    function it_implements_state_machine_interface(): void
    {
        $this->shouldImplement(StateMachineInterface::class);
    }

    function it_implements_legacy_state_machine_interface(): void
    {
        $this->shouldImplement(LegacyStateMachineInterface::class);
    }

    function it_should_be_an_alias_of_state_machine(): void
    {
        $this->shouldImplement(NewStateMachine::class);
    }
}
