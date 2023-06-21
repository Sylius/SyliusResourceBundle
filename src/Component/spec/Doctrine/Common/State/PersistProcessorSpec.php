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

namespace spec\Sylius\Component\Resource\Doctrine\Common\State;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Component\Resource\Metadata\Operation;

final class PersistProcessorSpec extends ObjectBehavior
{
    function let(ManagerRegistry $managerRegistry): void
    {
        $this->beConstructedWith($managerRegistry);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PersistProcessor::class);
    }

    function it_persists_data_when_manager_does_not_contains_the_resource_yet(
        ManagerRegistry $managerRegistry,
        Operation $operation,
        ObjectManager $manager,
    ): void {
        $data = new \stdClass();

        $managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager);

        $manager->contains($data)->willReturn(false);

        $manager->persist($data)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();
        $manager->refresh($data)->shouldBeCalled();

        $this->process($data, $operation, new Context());
    }

    function it_only_flush_when_manager_contains_the_resource(
        ManagerRegistry $managerRegistry,
        Operation $operation,
        ObjectManager $manager,
    ): void {
        $data = new \stdClass();

        $managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager);

        $manager->contains($data)->willReturn(true);
        $manager->getClassMetadata(\stdClass::class)->willReturn($data);

        $manager->persist($data)->shouldNotBeCalled();

        $manager->flush()->shouldBeCalled();
        $manager->refresh($data)->shouldBeCalled();

        $this->process($data, $operation, new Context());
    }

    function it_persists_when_it_is_deferred_explicitly(
        ManagerRegistry $managerRegistry,
        Operation $operation,
        ObjectManager $manager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $data = new \stdClass();

        $managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager);

        $manager->contains($data)->willReturn(true);

        $manager->getClassMetadata(\stdClass::class)->willReturn($classMetadataInfo);

        $classMetadataInfo->isChangeTrackingDeferredExplicit()->willReturn(true)->shouldBeCalled();

        $manager->persist($data)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();
        $manager->refresh($data)->shouldBeCalled();

        $this->process($data, $operation, new Context());
    }

    function it_does_nothing_when_data_is_not_managed_by_doctrine(
        ManagerRegistry $managerRegistry,
        Operation $operation,
    ): void {
        $data = new \stdClass();

        $managerRegistry->getManagerForClass(\stdClass::class)->willReturn(null);

        $this->process($data, $operation, new Context())->shouldReturn($data);
    }

    function it_does_nothing_when_data_is_not_an_object(
        Operation $operation,
    ): void {
        $this->process(1, $operation, new Context())->shouldReturn(1);
    }
}
