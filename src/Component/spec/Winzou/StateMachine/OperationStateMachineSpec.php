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

namespace Sylius\Resource\Tests\Winzou\StateMachine;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SM\Factory\Factory;
use SM\StateMachine\StateMachineInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Resource\Winzou\StateMachine\OperationStateMachine;

final class OperationStateMachineTest extends TestCase
{
    private OperationStateMachine $operationStateMachine;

    private Factory|MockObject $factory;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(Factory::class);
        $this->operationStateMachine = new OperationStateMachine($this->factory);
    }

    public function testItReturnsIfTransitionIsPossible(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');
        $stateMachine = $this->createMock(StateMachineInterface::class);

        $this->factory->method('get')->with($data, 'default')->willReturn($stateMachine);
        $stateMachine->method('can')->with('publish')->willReturn(true);

        $result = $this->operationStateMachine->can($data, $operation, new Context());

        $this->assertTrue($result);
    }

    public function testItAppliesTransition(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');
        $stateMachine = $this->createMock(StateMachineInterface::class);

        $this->factory->method('get')->with($data, 'default')->willReturn($stateMachine);
        $stateMachine->method('apply')->with('publish')->willReturn(true);

        $this->operationStateMachine->apply($data, $operation, new Context());

        $this->assertTrue(true);
    }

    public function testItThrowsAnExceptionWhenOperationHasNoDefinedTransition(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No State machine transition was found on operation "app_dummy_create".');

        $data = new \stdClass();
        $operation = new Create(name: 'app_dummy_create');
        $stateMachine = $this->createMock(StateMachineInterface::class);

        $this->factory->method('get')->with($data, 'default')->willReturn($stateMachine);

        $this->operationStateMachine->can($data, $operation, new Context());
    }

    public function testItThrowsAnExceptionWhenWinzouStateMachineIsNotAvailable(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "state-machine" if Winzou State Machine is not available. Try running "composer require winzou/state-machine-bundle".');

        $operationStateMachine = new OperationStateMachine(null);
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');

        $operationStateMachine->can($data, $operation, new Context());
    }

    public function testItThrowsAnExceptionWhenOperationDoesNotImplementAStateMachine(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(sprintf('Expected an instance of %s. Got: %s', StateMachineAwareOperationInterface::class, Index::class));

        $data = new \stdClass();
        $operation = new Index();

        $this->operationStateMachine->can($data, $operation, new Context());
    }
}
