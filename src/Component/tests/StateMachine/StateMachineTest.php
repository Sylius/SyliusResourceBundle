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

namespace Sylius\Resource\Tests\StateMachine;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\StateMachine\StateMachine;
use Sylius\Resource\Tests\Dummy\PullRequest;

final class StateMachineTest extends TestCase
{
    private StateMachine $stateMachine;

    protected function setUp(): void
    {
        $this->stateMachine = new StateMachine(new PullRequest(), [
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

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(StateMachine::class, $this->stateMachine);
    }

    public function testItGetsTransitionFromAState(): void
    {
        $this->assertSame('submit', $this->stateMachine->getTransitionFromState('start'));
    }

    public function testItGetsTransitionToAState(): void
    {
        $this->assertSame('submit', $this->stateMachine->getTransitionToState('test'));
    }
}
