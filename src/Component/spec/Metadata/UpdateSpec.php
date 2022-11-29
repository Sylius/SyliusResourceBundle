<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Resource\Metadata;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;

final class UpdateSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Update::class);
    }

    function it_is_an_operation(): void
    {
        $this->shouldHaveType(Operation::class);
    }

    function it_implements_update_operation_interface(): void
    {
        $this->shouldImplement(UpdateOperationInterface::class);
    }

    function it_has_update_name_by_default(): void
    {
        $this->getName()->shouldReturn('update');
    }

    function it_has_get_and_post_methods_by_default(): void
    {
        $this->getMethods()->shouldReturn(['GET', 'PUT']);
    }
}
