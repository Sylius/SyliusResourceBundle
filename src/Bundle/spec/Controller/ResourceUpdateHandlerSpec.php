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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandler;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Sylius\Resource\Model\ResourceInterface;

final class ResourceUpdateHandlerTest extends TestCase
{
    /** @var StateMachineInterface|MockObject */
    private MockObject $stateMachineMock;

    private ResourceUpdateHandler $resourceUpdateHandler;

    protected function setUp(): void
    {
        $this->stateMachineMock = $this->createMock(StateMachineInterface::class);
        $this->resourceUpdateHandler = new ResourceUpdateHandler($this->stateMachineMock);
    }

    public function testImplementsAResourceUpdateHandlerInterface(): void
    {
        $this->assertInstanceOf(ResourceUpdateHandlerInterface::class, $this->resourceUpdateHandler);
    }

    public function testAppliesAStateMachineTransition(): void
    {
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ObjectManager|MockObject $managerMock */
        $managerMock = $this->createMock(ObjectManager::class);
        $configurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $this->stateMachineMock->expects($this->once())->method('apply')->with($configurationMock, $resourceMock);
        $managerMock->expects($this->once())->method('flush');
        $this->resourceUpdateHandler->handle($resourceMock, $configurationMock, $managerMock);
    }

    public function testDoesNotApplyAStateMachineTransition(): void
    {
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ObjectManager|MockObject $managerMock */
        $managerMock = $this->createMock(ObjectManager::class);
        $configurationMock->expects($this->once())->method('hasStateMachine')->willReturn(false);
        $this->stateMachineMock->expects($this->never())->method('apply')->with($configurationMock, $resourceMock);
        $managerMock->expects($this->once())->method('flush');
        $this->resourceUpdateHandler->handle($resourceMock, $configurationMock, $managerMock);
    }
}
