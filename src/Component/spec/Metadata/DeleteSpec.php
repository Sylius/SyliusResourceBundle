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
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;

final class DeleteSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Delete::class);
    }

    function it_is_an_operation(): void
    {
        $this->shouldHaveType(Operation::class);
    }

    function it_implements_delete_operation_interface(): void
    {
        $this->shouldImplement(DeleteOperationInterface::class);
    }

    function it_has_delete_name_by_default(): void
    {
        $this->getName()->shouldReturn('delete');
    }

    function it_has_delete_methods_by_default(): void
    {
        $this->getMethods()->shouldReturn(['DELETE']);
    }
}
