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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandler;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;

final class ResourceDeleteHandlerTest extends TestCase
{
    private ResourceDeleteHandler $resourceDeleteHandler;

    protected function setUp(): void
    {
        $this->resourceDeleteHandler = new ResourceDeleteHandler();
    }

    public function testImplementsAResourceDeleteHandlerInterface(): void
    {
        $this->assertInstanceOf(ResourceDeleteHandlerInterface::class, $this->resourceDeleteHandler);
    }

    public function testRemovesResourceViaRepository(): void
    {
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $repositoryMock->expects($this->once())->method('remove')->with($resourceMock);
        $this->resourceDeleteHandler->handle($resourceMock, $repositoryMock);
    }
}
