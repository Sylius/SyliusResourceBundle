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
use Sylius\Component\Resource\Tests\Dummy\DummyController;

final class OperationSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Operation::class);
    }

    function it_has_no_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_could_have_a_name(): void
    {
        $this->withName('create')
            ->getName()
            ->shouldReturn('create')
        ;
    }

    function it_has_no_methods_by_default(): void
    {
        $this->getMethods()->shouldReturn(null);
    }

    function it_could_have_methods(): void
    {
        $this->withMethods(['POST', 'GET'])
            ->getMethods()
            ->shouldReturn(['POST', 'GET'])
        ;
    }

    function it_has_no_path_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_could_have_a_path(): void
    {
        $this->withPath('you_should_not_pass')
            ->getPath()
            ->shouldReturn('you_should_not_pass')
        ;
    }

    function it_has_no_route_prefix_by_default(): void
    {
        $this->getRoutePrefix()->shouldReturn(null);
    }

    function it_could_have_a_route_prefix(): void
    {
        $this->withRoutePrefix('/admin')
            ->getRoutePrefix()
            ->shouldReturn('/admin')
        ;
    }

    function it_has_no_controller_by_default(): void
    {
        $this->getController()->shouldReturn(null);
    }

    function it_could_have_a_controller(): void
    {
        $this->withController(DummyController::class)
            ->getController()
            ->shouldReturn(DummyController::class)
        ;
    }

    function it_has_no_template_by_default(): void
    {
        $this->getTemplate()->shouldReturn(null);
    }

    function it_could_have_a_template(): void
    {
        $this->withTemplate('book/show.html.twig')
            ->getTemplate()
            ->shouldReturn('book/show.html.twig')
        ;
    }
}
