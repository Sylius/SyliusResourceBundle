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

namespace Sylius\Bundle\ResourceBundle\Tests\Bundle\EventListener;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\ReflectionService;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Sylius\Bundle\ResourceBundle\EventListener\AbstractDoctrineListener;
use Sylius\Resource\Metadata\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AbstractDoctrineListenerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private AbstractDoctrineListener $listener;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $resourceRegistry = $this->getContainer()->get(RegistryInterface::class);
        $this->listener = new class($resourceRegistry) extends AbstractDoctrineListener {
            public function publicIsResource(ClassMetadata $metadata): bool
            {
                return $this->isResource($metadata);
            }

            public function publicGetReflectionService(): \Doctrine\Persistence\Mapping\ReflectionService
            {
                return $this->getReflectionService();
            }
        };
    }

    public function testIsResourceReturnsTrueForResourceInterface(): void
    {
        $metadata = $this->getClassMetadata(Book::class);

        $result = $this->listener->publicIsResource($metadata);

        $this->assertTrue($result);
    }

    public function testGetReflectionServiceReturnsRuntimeReflectionService(): void
    {
        $reflectionService = $this->listener->publicGetReflectionService();

        $this->assertInstanceOf(RuntimeReflectionService::class, $reflectionService);
    }

    public function testGetReflectionServiceReturnsSameInstanceOnMultipleCalls(): void
    {
        $firstCall = $this->listener->publicGetReflectionService();
        $secondCall = $this->listener->publicGetReflectionService();

        $this->assertSame($firstCall, $secondCall);
    }

    public function testConstructorAcceptsResourceRegistry(): void
    {
        $resourceRegistry = $this->getContainer()->get(RegistryInterface::class);
        $listener = new class($resourceRegistry) extends AbstractDoctrineListener {
        };

        $this->assertInstanceOf(AbstractDoctrineListener::class, $listener);
    }

    public function testIsResourceWorksWithDifferentEntities(): void
    {
        $bookMetadata = $this->getClassMetadata(Book::class);
        $userMetadata = $this->getClassMetadata(User::class);

        $this->assertTrue($this->listener->publicIsResource($bookMetadata));
        $this->assertTrue($this->listener->publicIsResource($userMetadata));
    }

    public function testReflectionServiceImplementsCorrectInterface(): void
    {
        $reflectionService = $this->listener->publicGetReflectionService();

        $this->assertInstanceOf(ReflectionService::class, $reflectionService);
    }

    public function testReflectionServiceIsLazilyInitialized(): void
    {
        $resourceRegistry = $this->getContainer()->get(RegistryInterface::class);
        $newListener = new class($resourceRegistry) extends AbstractDoctrineListener {
            public function hasReflectionService(): bool
            {
                return $this->getReflectionService() instanceof RuntimeReflectionService;
            }
        };

        $this->assertTrue($newListener->hasReflectionService());
    }

    private function getClassMetadata(string $className): ClassMetadata
    {
        return $this->entityManager->getClassMetadata($className);
    }
}
