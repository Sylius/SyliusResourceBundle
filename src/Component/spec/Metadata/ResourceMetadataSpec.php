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
use Sylius\Component\Resource\Exception\OperationNotFoundException;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Metadata\Update;

final class ResourceMetadataSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourceMetadata::class);
    }

    function it_has_no_resource_by_default(): void
    {
        $this->getResource()->shouldReturn(null);
    }

    function it_can_have_a_resource(): void
    {
        $resource = new Resource();

        $this->withResource($resource)
            ->getResource()
            ->shouldReturn($resource)
        ;
    }

    function it_can_get_an_operation_by_name(): void
    {
        $create = new Create();
        $update = new Update();
        $resource = new Resource(operations: [$create, $update]);

        $metadata = $this->withResource($resource);
        $metadata->getOperation('create')->shouldReturn($create);
        $metadata->getOperation('update')->shouldReturn($update);
    }

    function it_throws_an_operation_not_found_exception_when_operation_is_not_found(): void
    {
        $this->shouldThrow(OperationNotFoundException::class)
            ->during('getOperation', ['create'])
        ;
    }
}
