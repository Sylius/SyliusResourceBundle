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

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Resource\Metadata\Operation;

final class RemoveProcessorTest extends TestCase
{
    private ManagerRegistry|MockObject $managerRegistry;

    private RemoveProcessor $removeProcessor;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->removeProcessor = new RemoveProcessor($this->managerRegistry);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RemoveProcessor::class, $this->removeProcessor);
    }

    public function testItRemovesData(): void
    {
        $data = new \stdClass();
        $operation = $this->createMock(Operation::class);
        $manager = $this->createMock(ObjectManager::class);

        $this->managerRegistry->method('getManagerForClass')->with(\stdClass::class)->willReturn($manager);
        $manager->method('contains')->with($data)->willReturn(false);

        $manager->expects($this->once())->method('remove')->with($data);
        $manager->expects($this->once())->method('flush');

        $result = $this->removeProcessor->process($data, $operation, new Context());
        $this->assertSame($data, $result);
    }

    public function testItDoesNothingWhenDataIsNotManagedByDoctrine(): void
    {
        $data = new \stdClass();
        $operation = $this->createMock(Operation::class);

        $this->managerRegistry->method('getManagerForClass')->with(\stdClass::class)->willReturn(null);

        $this->assertNull($this->removeProcessor->process($data, $operation, new Context()));
    }

    public function testItDoesNothingWhenDataIsNotAnObject(): void
    {
        $operation = $this->createMock(Operation::class);

        $this->assertNull($this->removeProcessor->process(1, $operation, new Context()));
    }
}
