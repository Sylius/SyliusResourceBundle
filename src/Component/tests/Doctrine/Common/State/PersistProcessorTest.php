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

namespace Sylius\Resource\Tests\Doctrine\Common\State;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Resource\Metadata\Operation;

final class PersistProcessorTest extends TestCase
{
    private ManagerRegistry|MockObject $managerRegistry;

    private PersistProcessor $persistProcessor;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->persistProcessor = new PersistProcessor($this->managerRegistry);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(PersistProcessor::class, $this->persistProcessor);
    }

    public function testItPersistsDataWhenManagerDoesNotContainTheResourceYet(): void
    {
        $manager = $this->createMock(ObjectManager::class);
        $operation = $this->createMock(Operation::class);
        $data = new \stdClass();

        $this->managerRegistry->method('getManagerForClass')->with(\stdClass::class)->willReturn($manager);
        $manager->method('contains')->with($data)->willReturn(false);

        $manager->expects($this->once())->method('persist')->with($data);
        $manager->expects($this->once())->method('flush');
        $manager->expects($this->once())->method('refresh')->with($data);

        $this->persistProcessor->process($data, $operation, new Context());
    }

    public function testItOnlyFlushesWhenManagerContainsTheResource(): void
    {
        $manager = $this->createMock(ObjectManager::class);
        $operation = $this->createMock(Operation::class);
        $metadata = $this->createMock(ClassMetadata::class);
        $data = new \stdClass();

        $metadata->expects(self::once())->method('isChangeTrackingDeferredExplicit')->willReturn(false);

        $this->managerRegistry->method('getManagerForClass')->with(\stdClass::class)->willReturn($manager);
        $manager->method('contains')->with($data)->willReturn(true);
        $manager->method('getClassMetadata')->with(\stdClass::class)->willReturn($metadata);

        $manager->expects($this->never())->method('persist')->with($data);
        $manager->expects($this->once())->method('flush');
        $manager->expects($this->once())->method('refresh')->with($data);

        $this->persistProcessor->process($data, $operation, new Context());
    }

    public function testItPersistsWhenDeferredExplicitly(): void
    {
        $manager = $this->createMock(ObjectManager::class);
        $operation = $this->createMock(Operation::class);
        $classMetadataInfo = $this->createMock(ClassMetadata::class);
        $data = new \stdClass();

        $this->managerRegistry->method('getManagerForClass')->with(\stdClass::class)->willReturn($manager);
        $manager->method('contains')->with($data)->willReturn(true);
        $manager->method('getClassMetadata')->with(\stdClass::class)->willReturn($classMetadataInfo);

        $classMetadataInfo->expects($this->once())->method('isChangeTrackingDeferredExplicit')->willReturn(true);

        $manager->expects($this->once())->method('persist')->with($data);
        $manager->expects($this->once())->method('flush');
        $manager->expects($this->once())->method('refresh')->with($data);

        $this->persistProcessor->process($data, $operation, new Context());
    }

    public function testItDoesNothingWhenDataIsNotManagedByDoctrine(): void
    {
        $operation = $this->createMock(Operation::class);
        $data = new \stdClass();

        $this->managerRegistry->method('getManagerForClass')->with(\stdClass::class)->willReturn(null);

        $this->assertSame($data, $this->persistProcessor->process($data, $operation, new Context()));
    }

    public function testItDoesNothingWhenDataIsNotAnObject(): void
    {
        $operation = $this->createMock(Operation::class);

        $this->assertSame(1, $this->persistProcessor->process(1, $operation, new Context()));
    }
}
