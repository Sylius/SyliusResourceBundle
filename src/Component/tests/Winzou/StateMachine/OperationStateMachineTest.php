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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use SM\Factory\Factory;
use SM\StateMachine\StateMachineInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Resource\Winzou\StateMachine\OperationStateMachine;

final class OperationStateMachineTest extends TestCase
{
    use ProphecyTrait;

    private OperationStateMachine $operationStateMachine;

    private Factory|ObjectProphecy $factory;

    protected function setUp(): void
    {
        $this->factory = $this->prophesize(Factory::class);
        $this->operationStateMachine = new OperationStateMachine($this->factory->reveal());
    }

    public function testItReturnsIfTransitionIsPossible(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');
        $stateMachine = $this->prophesize(StateMachineInterface::class);

        $this->factory->get($data, 'default')->willReturn($stateMachine->reveal());
        $stateMachine->can('publish')->willReturn(true);

        $result = $this->operationStateMachine->can($data, $operation, new Context());

        $this->assertTrue($result);
    }

    public function testItAppliesTransition(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');
        $stateMachine = $this->prophesize(StateMachineInterface::class);

        $this->factory->get($data, 'default')->willReturn($stateMachine->reveal());
        $stateMachine->apply('publish')->willReturn(true);

        $this->operationStateMachine->apply($data, $operation, new Context());

        // No assertion needed as we're checking for exceptions, ensure apply is called
        $this->assertTrue(true);
    }

    public function testItThrowsAnExceptionWhenOperationHasNoDefinedTransition(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No State machine transition was found on operation "app_dummy_create".');

        $data = new \stdClass();
        $operation = new Create(name: 'app_dummy_create');
        $stateMachine = $this->prophesize(StateMachineInterface::class);

        $this->factory->get($data, 'default')->willReturn($stateMachine->reveal());

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
