<?php

declare(strict_types=1);

namespace Sylius\Resource\Tests\Doctrine\Common\State;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Resource\Metadata\Operation;

final class PersistProcessorTest extends TestCase
{
    use ProphecyTrait;

    private ManagerRegistry|ObjectProphecy $managerRegistry;
    private PersistProcessor $persistProcessor;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->prophesize(ManagerRegistry::class);
        $this->persistProcessor = new PersistProcessor($this->managerRegistry->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(PersistProcessor::class, $this->persistProcessor);
    }

    public function testItPersistsDataWhenManagerDoesNotContainTheResourceYet(): void
    {
        $manager = $this->prophesize(ObjectManager::class);
        $operation = $this->prophesize(Operation::class);
        $data = new \stdClass();

        $this->managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager);
        $manager->contains($data)->willReturn(false);

        $manager->persist($data)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();
        $manager->refresh($data)->shouldBeCalled();

        $this->persistProcessor->process($data, $operation->reveal(), new Context());
    }

    public function testItOnlyFlushesWhenManagerContainsTheResource(): void
    {
        $manager = $this->prophesize(ObjectManager::class);
        $operation = $this->prophesize(Operation::class);
        $data = new \stdClass();

        $this->managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager);
        $manager->contains($data)->willReturn(true);
        $manager->getClassMetadata(\stdClass::class)->willReturn($data);

        $manager->persist($data)->shouldNotBeCalled();
        $manager->flush()->shouldBeCalled();
        $manager->refresh($data)->shouldBeCalled();

        $this->persistProcessor->process($data, $operation->reveal(), new Context());
    }

    public function testItPersistsWhenDeferredExplicitly(): void
    {
        $manager = $this->prophesize(ObjectManager::class);
        $operation = $this->prophesize(Operation::class);
        $classMetadataInfo = $this->prophesize(ClassMetadataInfo::class);
        $data = new \stdClass();

        $this->managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager);
        $manager->contains($data)->willReturn(true);
        $manager->getClassMetadata(\stdClass::class)->willReturn($classMetadataInfo);

        $classMetadataInfo->isChangeTrackingDeferredExplicit()->willReturn(true)->shouldBeCalled();

        $manager->persist($data)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();
        $manager->refresh($data)->shouldBeCalled();

        $this->persistProcessor->process($data, $operation->reveal(), new Context());
    }

    public function testItDoesNothingWhenDataIsNotManagedByDoctrine(): void
    {
        $operation = $this->prophesize(Operation::class);
        $data = new \stdClass();

        $this->managerRegistry->getManagerForClass(\stdClass::class)->willReturn(null);

        $this->assertSame($data, $this->persistProcessor->process($data, $operation->reveal(), new Context()));
    }

    public function testItDoesNothingWhenDataIsNotAnObject(): void
    {
        $operation = $this->prophesize(Operation::class);

        $this->assertSame(1, $this->persistProcessor->process(1, $operation->reveal(), new Context()));
    }
}
