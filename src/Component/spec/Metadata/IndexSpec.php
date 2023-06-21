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
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Resource;

final class IndexSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Index::class);
    }

    function it_is_an_operation(): void
    {
        $this->shouldHaveType(Operation::class);
    }

    function it_implements_collection_operation_interface(): void
    {
        $this->shouldImplement(CollectionOperationInterface::class);
    }

    function it_has_no_resource_by_default(): void
    {
        $this->getResource()->shouldReturn(null);
    }

    function it_could_have_a_resource(): void
    {
        $resource = new Resource(alias: 'app.book');

        $this->withResource($resource)
            ->getResource()
            ->shouldReturn($resource)
        ;
    }

    function it_has_index_short_name_by_default(): void
    {
        $this->getShortName()->shouldReturn('index');
    }

    function it_has_get_methods_by_default(): void
    {
        $this->getMethods()->shouldReturn(['GET']);
    }
}
