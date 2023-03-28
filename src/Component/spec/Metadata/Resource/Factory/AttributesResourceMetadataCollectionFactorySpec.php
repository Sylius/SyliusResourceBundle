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
use Sylius\Component\Resource\Grid\State\RequestGridProvider;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Symfony\Request\State\Provider;
use Sylius\Component\Resource\Symfony\Request\State\Responder;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRouteNameFactory;
use Sylius\Component\Resource\Tests\Dummy\DummyMultiResourcesWithOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyOperationsWithoutResource;
use Sylius\Component\Resource\Tests\Dummy\DummyResource;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithAlias;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithDenormalizationContext;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithFormOptions;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithFormType;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithGrid;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithName;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithNormalizationContext;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithPluralName;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithRoutePrefix;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithSections;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithSectionsAndNestedOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithValidationContext;

final class AttributesResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(RegistryInterface $resourceRegistry): void
    {
        $this->beConstructedWith($resourceRegistry, new OperationRouteNameFactory(), 'symfony');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributesResourceMetadataCollectionFactory::class);
    }

    function it_creates_resource_metadata(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $metadataCollection = $this->create(DummyResourceWithAlias::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(1);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');
    }

    function it_creates_resource_metadata_without_resource_alias(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->getByClass(DummyResource::class)->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $metadataCollection = $this->create(DummyResource::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $metadataCollection->count()->shouldReturn(1);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');
        $resource->getClass()->shouldReturn(DummyResource::class);
    }

    function it_creates_resource_metadata_with_operations(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

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
        $resourceRegistry->get('app.order')->willReturn(Metadata::fromAliasAndConfiguration('app.order', [
            'driver' => 'order_driver',
            'classes' => [
                'model' => 'App\Order',
                'form' => 'App\Form\OrderType',
            ],
        ]));
        $resourceRegistry->get('app.cart')->willReturn(Metadata::fromAliasAndConfiguration('app.cart', [
            'driver' => 'cart_driver',
            'classes' => [
                'model' => 'App\Cart',
                'form' => 'App\Form\CartType',
            ],
        ]));

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
        $operation->getRepository()->shouldReturn('app.repository.order');
        $operation->getFormType()->shouldReturn('App\Form\OrderType');

        $operation = $metadataCollection->getOperation('app.cart', 'app_cart_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_cart_index');
        $operation->getMethods()->shouldReturn(['GET']);
        $operation->getRepository()->shouldReturn('app.repository.cart');
        $operation->getFormType()->shouldReturn('App\Form\CartType');

        $operation = $metadataCollection->getOperation('app.cart', 'app_cart_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('app_cart_show');
        $operation->getMethods()->shouldReturn(['GET']);
        $operation->getRepository()->shouldReturn('app.repository.cart');
        $operation->getFormType()->shouldReturn('App\Form\CartType');
    }

    function it_creates_multi_resources_metadata_with_sections(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

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

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('app_admin_dummy_create');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_admin_dummy_index');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_shop_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('app_shop_dummy_show');
    }

    function it_creates_multi_resources_metadata_with_sections_and_nested_operations(RegistryInterface $resourceRegistry): void
    {
        if (\PHP_VERSION_ID < 80100) {
            throw new SkippingException('Nested attributes are supported since PHP 8.1');
        }

        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

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

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('app_admin_dummy_create');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getName()->shouldReturn('app_admin_dummy_index');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_shop_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getName()->shouldReturn('app_shop_dummy_show');
    }

    function it_creates_operations_even_if_there_is_no_resource_attribute(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->getByClass(DummyOperationsWithoutResource::class)->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

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

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('app_dummy_create');
    }

    function it_creates_resource_metadata_with_form_type(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithFormType::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->getFormType()->shouldReturn('App\Form\DummyType');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->getFormType()->shouldReturn('App\Form\DummyType');
    }

    function it_creates_resource_metadata_with_form_options(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithFormOptions::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getFormOptions()->shouldReturn([
            'data_class' => 'App\Dummy',
            'html5' => false,
        ]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getFormOptions()->shouldReturn([
            'data_class' => 'App\Dummy',
            'html5' => true,
        ]);
    }

    function it_creates_resource_metadata_with_validation_context(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithValidationContext::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(2);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getValidationContext()->shouldReturn(['groups' => ['sylius']]);
        $operation->getFormOptions()->shouldReturn([
            'validation_groups' => ['sylius'],
            'data_class' => 'App\Dummy',
        ]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getValidationContext()->shouldReturn(['groups' => ['sylius']]);
        $operation->getFormOptions()->shouldReturn([
            'validation_groups' => ['sylius'],
            'data_class' => 'App\Dummy',
        ]);
    }

    function it_creates_resource_metadata_with_resource_name(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithName::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_book_create')->shouldReturn(true);
        $operations->has('app_book_update')->shouldReturn(true);
        $operations->has('app_book_index')->shouldReturn(true);
        $operations->has('app_book_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_create');
        $operation->getResource()->getName()->shouldReturn('book');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_update');
        $operation->getResource()->getName()->shouldReturn('book');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_index');
        $operation->getResource()->getName()->shouldReturn('book');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_show');
        $operation->getResource()->getName()->shouldReturn('book');
    }

    function it_creates_resource_metadata_with_resource_plural_name(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithPluralName::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->getResource()->getPluralName()->shouldReturn('books');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->getResource()->getPluralName()->shouldReturn('books');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->getResource()->getPluralName()->shouldReturn('books');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->getResource()->getPluralName()->shouldReturn('books');
    }

    function it_creates_resource_metadata_with_default_provider_on_http_operations(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->getProvider()->shouldReturn(Provider::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->getProvider()->shouldReturn(Provider::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->getProvider()->shouldReturn(Provider::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->getProvider()->shouldReturn(Provider::class);
    }

    function it_creates_resource_metadata_with_default_grid_provider_on_http_operations(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithGrid::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->getProvider()->shouldReturn(Provider::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->getProvider()->shouldReturn(Provider::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->getProvider()->shouldReturn(RequestGridProvider::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->getProvider()->shouldReturn(Provider::class);
    }

    function it_creates_resource_metadata_with_default_responder_on_http_operations(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->getResponder()->shouldReturn(Responder::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->getResponder()->shouldReturn(Responder::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->getResponder()->shouldReturn(Responder::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->getResponder()->shouldReturn(Responder::class);
    }

    function it_creates_resource_metadata_with_route_prefix(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithRoutePrefix::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getRoutePrefix()->shouldReturn('/admin');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getRoutePrefix()->shouldReturn('/admin');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getRoutePrefix()->shouldReturn('/admin');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getRoutePrefix()->shouldReturn('/admin');
    }

    function it_creates_resource_metadata_with_configured_state_machine_component(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'state_machine_component' => 'winzou',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithRoutePrefix::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getStateMachineComponent()->shouldReturn('winzou');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getStateMachineComponent()->shouldReturn('winzou');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->shouldHaveType(Index::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->shouldHaveType(Show::class);
    }

    function it_creates_resource_metadata_with_default_state_machine_component(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithRoutePrefix::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getStateMachineComponent()->shouldReturn('symfony');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getStateMachineComponent()->shouldReturn('symfony');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->shouldHaveType(Index::class);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->shouldHaveType(Show::class);
    }

    function it_creates_resource_metadata_with_normalization_context(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithNormalizationContext::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getNormalizationContext()->shouldReturn(['groups' => ['dummy:read']]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getNormalizationContext()->shouldReturn(['groups' => ['dummy:read']]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getNormalizationContext()->shouldReturn(['groups' => ['dummy:read']]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getNormalizationContext()->shouldReturn(['groups' => ['dummy:read']]);
    }

    function it_creates_resource_metadata_with_denormalization_context(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithDenormalizationContext::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getDenormalizationContext()->shouldReturn(['groups' => ['dummy:write']]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getDenormalizationContext()->shouldReturn(['groups' => ['dummy:write']]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getDenormalizationContext()->shouldReturn(['groups' => ['dummy:write']]);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getDenormalizationContext()->shouldReturn(['groups' => ['dummy:write']]);
    }

    function it_creates_resource_metadata_with_default_twig_context_factory(RegistryInterface $resourceRegistry): void
    {
        $resourceRegistry->get('app.dummy')->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]));

        $metadataCollection = $this->create(DummyResourceWithOperations::class);
        $metadataCollection->shouldHaveType(ResourceMetadataCollection::class);

        $resource = $metadataCollection->getIterator()->current();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');

        $operations = $resource->getOperations();
        $operations->shouldHaveType(Operations::class);

        $operations->count()->shouldReturn(4);
        $operations->has('app_dummy_create')->shouldReturn(true);
        $operations->has('app_dummy_update')->shouldReturn(true);
        $operations->has('app_dummy_index')->shouldReturn(true);
        $operations->has('app_dummy_show')->shouldReturn(true);

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $operation->shouldHaveType(Create::class);
        $operation->getTwigContextFactory()->shouldReturn('sylius.twig.context.factory.default');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $operation->shouldHaveType(Update::class);
        $operation->getTwigContextFactory()->shouldReturn('sylius.twig.context.factory.default');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $operation->shouldHaveType(Index::class);
        $operation->getTwigContextFactory()->shouldReturn('sylius.twig.context.factory.default');

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $operation->shouldHaveType(Show::class);
        $operation->getTwigContextFactory()->shouldReturn('sylius.twig.context.factory.default');
    }
}
