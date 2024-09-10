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
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Resource\Metadata\Operation;

final class RemoveProcessorTest extends TestCase
{
    use ProphecyTrait;

    private ManagerRegistry|ObjectProphecy $managerRegistry;

    private RemoveProcessor $removeProcessor;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->prophesize(ManagerRegistry::class);
        $this->removeProcessor = new RemoveProcessor($this->managerRegistry->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RemoveProcessor::class, $this->removeProcessor);
    }

    public function testItRemovesData(): void
    {
        $data = new \stdClass();
        $operation = $this->prophesize(Operation::class)->reveal();
        $manager = $this->prophesize(ObjectManager::class);

        $this->managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager->reveal());
        $manager->contains($data)->willReturn(false);

        $manager->remove($data)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->removeProcessor->process($data, $operation, new Context());
    }

    public function testItDoesNothingWhenDataIsNotManagedByDoctrine(): void
    {
        $data = new \stdClass();
        $operation = $this->prophesize(Operation::class)->reveal();

        $this->managerRegistry->getManagerForClass(\stdClass::class)->willReturn(null);

        $this->assertNull($this->removeProcessor->process($data, $operation, new Context()));
    }

    public function testItDoesNothingWhenDataIsNotAnObject(): void
    {
        $operation = $this->prophesize(Operation::class)->reveal();

        $this->assertNull($this->removeProcessor->process(1, $operation, new Context()));
    }
}
