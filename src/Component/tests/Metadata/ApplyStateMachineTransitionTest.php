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

namespace Sylius\Resource\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\ApplyStateMachineTransition;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Resource\Metadata\UpdateOperationInterface;

final class ApplyStateMachineTransitionTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $operation = new ApplyStateMachineTransition();

        $this->assertInstanceOf(ApplyStateMachineTransition::class, $operation);
    }

    public function testIsAnOperation(): void
    {
        $operation = new ApplyStateMachineTransition();

        $this->assertInstanceOf(Operation::class, $operation);
    }

    public function testImplementsUpdateOperationInterface(): void
    {
        $operation = new ApplyStateMachineTransition();

        $this->assertInstanceOf(UpdateOperationInterface::class, $operation);
    }

    public function testImplementsStateMachineAwareOperationInterface(): void
    {
        $operation = new ApplyStateMachineTransition();

        $this->assertInstanceOf(StateMachineAwareOperationInterface::class, $operation);
    }

    public function testHasNoResourceByDefault(): void
    {
        $operation = new ApplyStateMachineTransition();

        $this->assertNull($operation->getResource());
    }

    public function testCouldHaveAResource(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new ApplyStateMachineTransition();

        $operationWithResource = $operation->withResource($resource);

        $this->assertSame($resource, $operationWithResource->getResource());
    }

    public function testHasBulkDeleteShortNameByDefault(): void
    {
        $operation = new ApplyStateMachineTransition();

        $this->assertSame('apply_state_machine_transition', $operation->getShortName());
    }

    public function testHasDeleteMethodsByDefault(): void
    {
        $operation = new ApplyStateMachineTransition();

        $this->assertSame(['PUT', 'PATCH', 'POST'], $operation->getMethods());
    }
}
