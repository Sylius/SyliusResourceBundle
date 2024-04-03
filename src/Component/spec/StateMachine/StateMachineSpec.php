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

namespace spec\Sylius\Resource\StateMachine;

use App\Entity\PullRequest;
use PhpSpec\ObjectBehavior;
use Sylius\Resource\StateMachine\StateMachine;

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

    function it_gets_transition_from_a_state(): void
    {
        $this->getTransitionFromState('start')->shouldReturn('submit');
    }

    function it_gets_transition_to_a_state(): void
    {
        $this->getTransitionToState('test')->shouldReturn('submit');
    }
}
