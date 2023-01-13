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

namespace spec\Sylius\Component\Resource\Metadata\Resource\Factory;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Tests\Dummy\DummyMultiResourcesWithOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyOperationsWithoutResource;
use Sylius\Component\Resource\Tests\Dummy\DummyResource;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithSections;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithSectionsAndNestedOperations;

final class AttributesResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(RegistryInterface $resourceRegistry): void
    {
        $this->beConstructedWith($resourceRegistry);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributesResourceMetadataCollectionFactory::class);
    }

    function it_creates_resource_metadata(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $metadataCollection = $this->create(DummyResource::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(1);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');
    }

    function it_creates_resource_metadata_with_operations(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $metadataCollection = $this->create(DummyResourceWithOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);
    }

    function it_creates_multi_resources_metadata_with_operations(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.order')->willReturn(Metadata::fromAliasAndConfiguration('app.order', ['driver' => 'order_driver']));
        $resourceRegistry->get('app.cart')->willReturn(Metadata::fromAliasAndConfiguration('app.cart', ['driver' => 'cart_driver']));

        $metadataCollection = $this->create(DummyMultiResourcesWithOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(2);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.order');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('app_order_index')->shouldReturn(true);
        $operations->has('app_order_create')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.order', 'app_order_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_order_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.cart', 'app_cart_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_cart_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.cart', 'app_cart_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('app_cart_show');
        $operation->getMethods()->shouldReturn(['GET']);
    }

    function it_creates_multi_resources_metadata_with_sections(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $metadataCollection = $this->create(DummyResourceWithSections::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(2);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('app_admin_dummy_index')->shouldReturn(true);
        $operations->has('app_admin_dummy_create')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_admin_dummy_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('app_admin_dummy_create');
        $operation->getMethods()->shouldReturn(['GET', 'POST']);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_admin_dummy_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_shop_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('app_shop_dummy_show');
        $operation->getMethods()->shouldReturn(['GET']);
    }

    function it_creates_multi_resources_metadata_with_sections_and_nested_operations(RegistryInterface $resourceRegistry): void
    {
        if (\PHP_VERSION_ID < 80100) {
            throw new SkippingException('Nested attributes are supported since PHP 8.1');
        }

        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $metadataCollection = $this->create(DummyResourceWithSectionsAndNestedOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(2);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('app_admin_dummy_index')->shouldReturn(true);
        $operations->has('app_admin_dummy_create')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_admin_dummy_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('app_admin_dummy_create');
        $operation->getMethods()->shouldReturn(['GET', 'POST']);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_admin_dummy_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_shop_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('app_shop_dummy_show');
        $operation->getMethods()->shouldReturn(['GET']);
    }

    function it_creates_operations_even_if_there_is_no_resource_attribute(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->getByClass(DummyOperationsWithoutResource::class)->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $metadataCollection = $this->create(DummyOperationsWithoutResource::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(1);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_create')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_dummy_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('app_dummy_create');
        $operation->getMethods()->shouldReturn(['GET', 'POST']);
    }
}
