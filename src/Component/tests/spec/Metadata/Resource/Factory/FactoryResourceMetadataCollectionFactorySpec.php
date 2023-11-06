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

namespace spec\Sylius\Resource\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\FactoryResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class FactoryResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($resourceRegistry, $decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FactoryResourceMetadataCollectionFactory::class);
    }

    function it_configures_factory_if_operation_implements_the_interface(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
        MetadataInterface $resourceConfiguration,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.book')->willReturn($resourceConfiguration);

        $resourceConfiguration->getDriver()->willReturn('doctrine/orm');
        $resourceConfiguration->getServiceId('factory')->willReturn('app.factory.book');

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getFactory()->shouldReturn('app.factory.book');
    }
}
