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

namespace spec\Sylius\Component\Resource\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Grid\State\RequestGridProvider;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ProviderResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Symfony\Request\State\Provider;

final class ProviderResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProviderResourceMetadataCollectionFactory::class);
    }

    function it_creates_resource_metadata_with_default_provider_on_http_operations(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

        $index = (new Index(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $index = $resourceMetadataCollection->getOperation('app.book', 'app_book_index');
        $index->getProvider()->shouldReturn(Provider::class);
    }

    function it_configures_request_grid_provider_if_operation_has_a_grid(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

        $index = (new Index(name: 'app_book_index', grid: 'app_book'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $index = $resourceMetadataCollection->getOperation('app.book', 'app_book_index');
        $index->getProvider()->shouldReturn(RequestGridProvider::class);
    }
}
