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
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Section;
use Sylius\Component\Resource\Metadata\Update;

final class SectionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Section::class);
    }

    function it_has_no_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_can_have_a_name(): void
    {
        $this->withName('admin')
            ->getName()
            ->shouldReturn('admin')
        ;
    }

    function it_has_no_route_prefix_by_default(): void
    {
        $this->getRoutePrefix()->shouldReturn(null);
    }

    function it_can_have_a_route_prefix(): void
    {
        $this->withRoutePrefix('/admin')
            ->getRoutePrefix()
            ->shouldReturn('/admin')
        ;
    }

    function it_has_no_templates_dir_by_default(): void
    {
        $this->getTemplatesDir()->shouldReturn(null);
    }

    function it_can_have_a_templates_dir_prefix(): void
    {
        $this->withTemplatesDir('admin/book')
            ->getTemplatesDir()
            ->shouldReturn('admin/book')
        ;
    }

    function it_has_no_operations_by_default(): void
    {
        $this->getOperations()->shouldReturn(null);
    }

    function it_can_have_operations(): void
    {
        $operations = new Operations();

        $this->withOperations($operations)
            ->getOperations()
            ->shouldReturn($operations)
        ;
    }

    function it_can_be_constructed_with_operations(): void
    {
        $this->beConstructedWith(null, null, null, [new Create(), new Update()]);

        $this->getOperations()->count()->shouldReturn(2);
    }
}
