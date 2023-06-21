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

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Component\Resource\Metadata\Operation;

final class RemoveProcessorSpec extends ObjectBehavior
{
    function let(ManagerRegistry $managerRegistry): void
    {
        $this->beConstructedWith($managerRegistry);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RemoveProcessor::class);
    }

    function it_removes_data(
        ManagerRegistry $managerRegistry,
        Operation $operation,
        ObjectManager $manager,
    ): void {
        $data = new \stdClass();

        $managerRegistry->getManagerForClass(\stdClass::class)->willReturn($manager);

        $manager->contains($data)->willReturn(false);

        $manager->remove($data)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->process($data, $operation, new Context());
    }

    function it_does_nothing_when_data_is_not_managed_by_doctrine(
        ManagerRegistry $managerRegistry,
        Operation $operation,
    ): void {
        $data = new \stdClass();

        $managerRegistry->getManagerForClass(\stdClass::class)->willReturn(null);

        $this->process($data, $operation, new Context())->shouldReturn(null);
    }

    function it_does_nothing_when_data_is_not_an_object(
        Operation $operation,
    ): void {
        $this->process(1, $operation, new Context())->shouldReturn(null);
    }
}
