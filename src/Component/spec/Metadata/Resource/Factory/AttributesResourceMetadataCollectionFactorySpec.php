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
use Sylius\Component\Resource\Metadata\Operations;
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

class AttributesResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributesResourceMetadataCollectionFactory::class);
    }

    function it_creates_resource_metadata(): void
    {
        $metadataCollection = $this->create(DummyResource::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(1);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');
    }

    function it_creates_resource_metadata_with_operations(): void
    {
        $metadataCollection = $this->create(DummyResourceWithOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('index')->shouldReturn(true);
        $operations->has('create')->shouldReturn(true);
        $operations->has('update')->shouldReturn(true);
        $operations->has('show')->shouldReturn(true);
    }

    function it_creates_multi_resources_metadata_with_operations(): void
    {
        $metadataCollection = $this->create(DummyMultiResourcesWithOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(2);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.order');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('index')->shouldReturn(true);
        $operations->has('create')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.order', 'index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.cart', 'index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.cart', 'show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('show');
        $operation->getMethods()->shouldReturn(['GET']);
    }

    function it_creates_multi_resources_metadata_with_sections(): void
    {
        $metadataCollection = $this->create(DummyResourceWithSections::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(2);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('admin_index')->shouldReturn(true);
        $operations->has('admin_create')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'admin_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('admin_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'admin_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('admin_create');
        $operation->getMethods()->shouldReturn(['GET', 'POST']);

        $operation = $metadataCollection->getOperation('app.dummy', 'shop_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('shop_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'shop_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('shop_show');
        $operation->getMethods()->shouldReturn(['GET']);
    }

    function it_creates_multi_resources_metadata_with_sections_and_nested_operations(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            throw new SkippingException('Nested attributes are supported since PHP 8.1');
        }

        $metadataCollection = $this->create(DummyResourceWithSectionsAndNestedOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(2);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('admin_index')->shouldReturn(true);
        $operations->has('admin_create')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'admin_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('admin_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'admin_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('admin_create');
        $operation->getMethods()->shouldReturn(['GET', 'POST']);

        $operation = $metadataCollection->getOperation('app.dummy', 'shop_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('shop_index');
        $operation->getMethods()->shouldReturn(['GET']);

        $operation = $metadataCollection->getOperation('app.dummy', 'shop_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('shop_show');
        $operation->getMethods()->shouldReturn(['GET']);
    }

    function it_throws_a_runtime_exception_when_no_resource_attribute_was_found(): void
    {
        $this->shouldThrow(new \RuntimeException(sprintf('No Resource attribute was found on %s', DummyOperationsWithoutResource::class)))
            ->during('create', [DummyOperationsWithoutResource::class])
        ;
    }
}
