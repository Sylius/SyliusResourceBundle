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

namespace spec\Sylius\Component\Resource\Metadata\Resource;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\ResourceMetadata;

final class ResourceMetadataCollectionSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith([
            (new ResourceMetadata('app.dummy'))->withOperations(new Operations([
                'app_dummy_index' => new Index(),
                'app_dummy_create' => new Create(),
            ])),
        ]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourceMetadataCollection::class);
    }

    function it_can_get_a_resource_operation(): void
    {
        $this->getOperation('app.dummy', 'app_dummy_index')->shouldHaveType(Index::class);
    }

    function it_throws_an_exception_when_operation_was_not_found(): void
    {
        $this->shouldThrow(new \RuntimeException(
            'Operation "app_dummy_not_found" for "app.dummy" resource was not found.',
        ))
            ->during('getOperation', ['app.dummy', 'app_dummy_not_found'])
        ;
    }
}
