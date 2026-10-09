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

namespace Sylius\Bundle\ResourceBundle\Tests\Doctrine\ORM\Handler;

use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\Handler\ResourceDeleteHandler;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Exception\DeleteHandlingException;
use Sylius\Resource\Model\ResourceInterface;

final class ResourceDeleteHandlerTest extends TestCase
{
    private ResourceDeleteHandlerInterface&MockObject $decoratedHandler;

    private ManagerRegistry&MockObject $managerRegistry;

    private EntityManagerInterface&MockObject $entityManager;

    private ResourceInterface&MockObject $resource;

    /** @var RepositoryInterface<ResourceInterface>&MockObject */
    private RepositoryInterface&MockObject $repository;

    private ResourceDeleteHandler $handler;

    protected function setUp(): void
    {
        $this->decoratedHandler = $this->createMock(ResourceDeleteHandlerInterface::class);
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->resource = $this->createMock(ResourceInterface::class);
        $this->repository = $this->createMock(RepositoryInterface::class);

        $this->handler = new ResourceDeleteHandler($this->decoratedHandler, $this->managerRegistry);
    }

    public function testItCommitsTheTransactionAroundASuccessfulDelete(): void
    {
        $this->managerRegistry->method('getManagerForClass')->willReturn($this->entityManager);

        $this->entityManager->expects($this->once())->method('beginTransaction');
        $this->decoratedHandler->expects($this->once())->method('handle')->with($this->resource, $this->repository);
        $this->entityManager->expects($this->once())->method('commit');
        $this->entityManager->expects($this->never())->method('rollback');

        $this->handler->handle($this->resource, $this->repository);
    }

    public function testItTurnsAForeignKeyViolationIntoAConflict(): void
    {
        $this->managerRegistry->method('getManagerForClass')->willReturn($this->entityManager);

        $violation = new ForeignKeyConstraintViolationException($this->createMock(DriverException::class), null);
        $this->decoratedHandler->method('handle')->willThrowException($violation);
        $this->entityManager->expects($this->once())->method('rollback');
        $this->entityManager->expects($this->never())->method('commit');

        try {
            $this->handler->handle($this->resource, $this->repository);
            $this->fail('DeleteHandlingException was not thrown.');
        } catch (DeleteHandlingException $exception) {
            $this->assertSame('delete_error', $exception->getFlash());
            $this->assertSame(409, $exception->getApiResponseCode());
            $this->assertSame($violation, $exception->getPrevious());
        }
    }

    public function testItRollsBackAndRethrowsAnyOtherFailure(): void
    {
        $this->managerRegistry->method('getManagerForClass')->willReturn($this->entityManager);

        $failure = new DeleteHandlingException();
        $this->decoratedHandler->method('handle')->willThrowException($failure);
        $this->entityManager->expects($this->once())->method('rollback');

        $this->expectExceptionObject($failure);

        $this->handler->handle($this->resource, $this->repository);
    }

    public function testItLeavesNonOrmManagedResourcesToTheDecoratedHandler(): void
    {
        $this->managerRegistry->method('getManagerForClass')->willReturn($this->createMock(ObjectManager::class));

        $this->decoratedHandler->expects($this->once())->method('handle')->with($this->resource, $this->repository);

        $this->handler->handle($this->resource, $this->repository);
    }
}
