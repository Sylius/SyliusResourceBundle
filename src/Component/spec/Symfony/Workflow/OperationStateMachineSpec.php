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

namespace Sylius\Resource\Tests\Symfony\Workflow;

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
    private Registry $registry;

    private OperationStateMachine $operationStateMachine;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(Registry::class);
        $this->operationStateMachine = new OperationStateMachine($this->registry);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(OperationStateMachine::class, $this->operationStateMachine);
    }

    public function testItReturnsIfTransitionIsPossible(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');
        $workflow = $this->createMock(Workflow::class);

        $this->registry->method('get')->with($data, null)->willReturn($workflow);
        $workflow->method('can')->with($data, 'publish')->willReturn(true);

        $result = $this->operationStateMachine->can($data, $operation, new Context());

        $this->assertTrue($result);
    }

    public function testItAppliesTransition(): void
    {
        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');
        $workflow = $this->createMock(Workflow::class);
        $marking = $this->createMock(Marking::class);

        $this->registry->method('get')->with($data, null)->willReturn($workflow);
        $workflow->expects($this->once())->method('apply')->with($data, 'publish')->willReturn($marking);

        $this->operationStateMachine->apply($data, $operation, new Context());
    }

    public function testItThrowsAnExceptionWhenOperationHasNoDefinedTransition(): void
    {
        $data = new \stdClass();
        $operation = new Create(name: 'app_dummy_create');
        $workflow = $this->createMock(Workflow::class);

        $this->registry->method('get')->with($data, null)->willReturn($workflow);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No State machine transition was found on operation "app_dummy_create".');

        $this->operationStateMachine->can($data, $operation, new Context());
    }

    public function testItThrowsAnExceptionWhenSymfonyWorkflowIsNotAvailable(): void
    {
        $operationStateMachine = new OperationStateMachine(null);

        $data = new \stdClass();
        $operation = new Create(stateMachineTransition: 'publish');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "state-machine" if Symfony workflow is not available. Try running "composer require symfony/workflow".');

        $operationStateMachine->can($data, $operation, new Context());
    }

    public function testItThrowsAnExceptionWhenOperationDoesNotImplementAStateMachine(): void
    {
        $data = new \stdClass();
        $operation = new Index();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(sprintf('Expected an instance of %s. Got: %s', StateMachineAwareOperationInterface::class, Index::class));

        $this->operationStateMachine->can($data, $operation, new Context());
    }
}
