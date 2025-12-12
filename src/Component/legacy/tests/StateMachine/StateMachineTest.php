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

namespace Sylius\Component\Resource\Tests\StateMachine;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\StateMachine\StateMachine;
use Sylius\Component\Resource\StateMachine\StateMachineInterface as LegacyStateMachineInterface;
use Sylius\Resource\StateMachine\StateMachine as NewStateMachine;
use Sylius\Resource\StateMachine\StateMachineInterface;
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

    public function testItImplementsStateMachineInterface(): void
    {
        $this->assertInstanceOf(StateMachineInterface::class, $this->stateMachine);
    }

    public function testItImplementsLegacyStateMachineInterface(): void
    {
        $this->assertInstanceOf(LegacyStateMachineInterface::class, $this->stateMachine);
    }

    public function testItShouldBeAnAliasOfStateMachine(): void
    {
        $this->assertInstanceOf(NewStateMachine::class, $this->stateMachine);
    }
}
