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

namespace Tests\Sylius\Resource\Symfony\Workflow;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Resource\Symfony\Workflow\OperationStateMachine;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

final class OperationStateMachineTest extends TestCase
{
    private OperationStateMachine $operationStateMachine;

    protected function setUp(): void
    {
        $registry = $this->createMock(Registry::class);
        $this->operationStateMachine = new OperationStateMachine($registry);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(OperationStateMachine::class, $this->operationStateMachine);
    }

    public function testReturnsIfTransitionIsPossible(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');

        $registry = $this->createMock(Registry::class);
        $workflow = $this->createMock(Workflow::class);
        $registry->method('get')->with($data, null)->willReturn($workflow);
        $workflow->method('can')->with($data, 'publish')->willReturn(true);

        $this->operationStateMachine = new OperationStateMachine($registry);

        $this->assertTrue($this->operationStateMachine->can($data, $operation, new Context()));
    }

    public function testAppliesTransition(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');

        $registry = $this->createMock(Registry::class);
        $workflow = $this->createMock(Workflow::class);
        $marking = $this->createMock(Marking::class);

        $registry->method('get')->with($data, null)->willReturn($workflow);
        $workflow->expects($this->once())->method('apply')->with($data, 'publish')->willReturn($marking);

        $this->operationStateMachine = new OperationStateMachine($registry);

        $this->operationStateMachine->apply($data, $operation, new Context());
    }

    public function testThrowsExceptionWhenOperationHasNoDefinedTransition(): void
    {
        $data = new \stdClass();
        $operation = new Create(name: 'app_dummy_create');

        $registry = $this->createMock(Registry::class);
        $registry->method('get')->with($data, null)->willReturn($this->createMock(Workflow::class));

        $this->operationStateMachine = new OperationStateMachine($registry);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No State machine transition was found on operation "app_dummy_create".');
        $this->operationStateMachine->can($data, $operation, new Context());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No State machine transition was found on operation "app_dummy_create".');
        $this->operationStateMachine->apply($data, $operation, new Context());
    }

    public function testThrowsExceptionWhenSymfonyWorkflowIsNotAvailable(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "state-machine" if Symfony workflow is not available. Try running "composer require symfony/workflow".');

        $this->operationStateMachine = new OperationStateMachine(null);
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');
        $this->operationStateMachine->can($data, $operation, new Context());
    }

    public function testThrowsExceptionWhenOperationDoesNotImplementAStateMachine(): void
    {
        $data = new \stdClass();
        $operation = new Index();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(sprintf('Expected an instance of %s. Got: %s', StateMachineAwareOperationInterface::class, Index::class));

        $this->operationStateMachine->can($data, $operation, new Context());
    }
}
