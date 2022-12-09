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
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\ShowOperationInterface;

final class ShowSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Show::class);
    }

    function it_is_an_operation(): void
    {
        $this->shouldHaveType(Operation::class);
    }

    function it_implements_show_operation_interface(): void
    {
        $this->shouldImplement(ShowOperationInterface::class);
    }

    function it_has_show_name_by_default(): void
    {
        $this->getName()->shouldReturn('show');
    }

    function it_has_get_methods_by_default(): void
    {
        $this->getMethods()->shouldReturn(['GET']);
    }
}
