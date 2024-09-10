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

namespace Sylius\Resource\Tests\Metadata\Resource\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Tests\Dummy\DummyMultiResourcesWithOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyOperationsWithoutResource;
use Sylius\Component\Resource\Tests\Dummy\DummyResource;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithAlias;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithDenormalizationContext;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithFormOptions;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithFormType;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithName;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithNormalizationContext;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithPluralName;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithRoutePrefix;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithSections;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithSectionsAndNestedOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithValidationContext;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\Request\State\Responder;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;

final class AttributesResourceMetadataCollectionFactoryTest extends TestCase
{
    private AttributesResourceMetadataCollectionFactory $factory;

    private $resourceRegistry;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->createMock(RegistryInterface::class);
        $this->factory = new AttributesResourceMetadataCollectionFactory($this->resourceRegistry, new OperationRouteNameFactory());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(AttributesResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItCreatesResourceMetadata(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);
        $this->resourceRegistry->method('get')->willReturn($metadata);

        $metadataCollection = $this->factory->create(DummyResourceWithAlias::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);
        $this->assertCount(1, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());
    }

    public function testItCreatesResourceMetadataWithoutResourceAlias(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);
        $this->resourceRegistry->method('getByClass')->willReturn($metadata);

        $metadataCollection = $this->factory->create(DummyResource::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);
        $this->assertCount(1, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());
        $this->assertSame(DummyResource::class, $resource->getClass());
    }

    public function testItCreatesResourceMetadataWithOperations(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]);
        $this->resourceRegistry->method('get')->willReturn($metadata);

        $metadataCollection = $this->factory->create(DummyResourceWithOperations::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);

        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));
        $this->assertTrue($operations->has('app_dummy_show'));
    }

    public function testItCreatesMultiResourcesMetadataWithOperations(): void
    {
        $orderMetadata = Metadata::fromAliasAndConfiguration('app.order', [
            'driver' => 'order_driver',
            'classes' => [
                'model' => 'App\Order',
                'form' => 'App\Form\OrderType',
            ],
        ]);
        $cartMetadata = Metadata::fromAliasAndConfiguration('app.cart', [
            'driver' => 'cart_driver',
            'classes' => [
                'model' => 'App\Cart',
                'form' => 'App\Form\CartType',
            ],
        ]);
        $this->resourceRegistry->method('get')
            ->willReturnMap([
                ['app.order', $orderMetadata],
                ['app.cart', $cartMetadata],
            ]);

        $metadataCollection = $this->factory->create(DummyMultiResourcesWithOperations::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);
        $this->assertCount(2, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.order', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(2, $operations);
        $this->assertTrue($operations->has('app_order_index'));
        $this->assertTrue($operations->has('app_order_create'));

        $operation = $metadataCollection->getOperation('app.order', 'app_order_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('app_order_index', $operation->getName());
        $this->assertSame(['GET'], $operation->getMethods());
        $this->assertSame('app.repository.order', $operation->getRepository());
        $this->assertSame('App\Form\OrderType', $operation->getFormType());

        $operation = $metadataCollection->getOperation('app.cart', 'app_cart_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('app_cart_index', $operation->getName());
        $this->assertSame(['GET'], $operation->getMethods());
        $this->assertSame('app.repository.cart', $operation->getRepository());
        $this->assertSame('App\Form\CartType', $operation->getFormType());

        $operation = $metadataCollection->getOperation('app.cart', 'app_cart_show');
        $this->assertInstanceOf(Show::class, $operation);
        $this->assertSame('app_cart_show', $operation->getName());
        $this->assertSame(['GET'], $operation->getMethods());
        $this->assertSame('app.repository.cart', $operation->getRepository());
        $this->assertSame('App\Form\CartType', $operation->getFormType());
    }

    public function testItCreatesMultiResourcesMetadataWithSections(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]);
        $this->resourceRegistry->method('get')->willReturn($metadata);

        $metadataCollection = $this->factory->create(DummyResourceWithSections::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);
        $this->assertCount(2, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(2, $operations);
        $this->assertTrue($operations->has('app_admin_dummy_index'));
        $this->assertTrue($operations->has('app_admin_dummy_create'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('app_admin_dummy_index', $operation->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame('app_admin_dummy_create', $operation->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_shop_dummy_show');
        $this->assertInstanceOf(Show::class, $operation);
        $this->assertSame('app_shop_dummy_show', $operation->getName());
    }

    public function testItCreatesMultiResourcesMetadataWithSectionsAndNestedOperations(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('Nested attributes are supported since PHP 8.1');
        }

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
            'classes' => [
                'model' => 'App\Dummy',
                'form' => 'App\Form',
            ],
        ]);
        $this->resourceRegistry->method('get')->willReturn($metadata);

        $metadataCollection = $this->factory->create(DummyResourceWithSectionsAndNestedOperations::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);
        $this->assertCount(2, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(2, $operations);
        $this->assertTrue($operations->has('app_admin_dummy_index'));
        $this->assertTrue($operations->has('app_admin_dummy_create'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('app_admin_dummy_index', $operation->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame('app_admin_dummy_create', $operation->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_admin_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('app_admin_dummy_index', $operation->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_shop_dummy_show');
        $this->assertInstanceOf(Show::class, $operation);
        $this->assertSame('app_shop_dummy_show', $operation->getName());
    }

    public function testItCreatesOperationsEvenIfThereIsNoResourceAttribute(): void
    {
        $this->resourceRegistry
            ->method('getByClass')
            ->with(DummyOperationsWithoutResource::class)
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']));

        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyOperationsWithoutResource::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);
        $this->assertCount(1, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(2, $operations);
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_create'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('app_dummy_index', $operation->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame('app_dummy_create', $operation->getName());
    }

    public function testItCreatesResourceMetadataWithFormType(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => ['model' => 'App\Dummy'],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithFormType::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(2, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertSame('App\Form\DummyType', $operation->getFormType());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertSame('App\Form\DummyType', $operation->getFormType());
    }

    public function testItCreatesResourceMetadataWithFormOptions(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithFormOptions::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(2, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame([
            'data_class' => 'App\Dummy',
            'html5' => false,
        ], $operation->getFormOptions());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertInstanceOf(Update::class, $operation);
        $this->assertSame([
            'data_class' => 'App\Dummy',
            'html5' => true,
        ], $operation->getFormOptions());
    }

    public function testItCreatesResourceMetadataWithValidationContext(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithValidationContext::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(2, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame(['groups' => ['sylius']], $operation->getValidationContext());
        $this->assertSame([
            'validation_groups' => ['sylius'],
            'data_class' => 'App\Dummy',
        ], $operation->getFormOptions());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertInstanceOf(Update::class, $operation);
        $this->assertSame(['groups' => ['sylius']], $operation->getValidationContext());
        $this->assertSame([
            'validation_groups' => ['sylius'],
            'data_class' => 'App\Dummy',
        ], $operation->getFormOptions());
    }

    public function testItCreatesResourceMetadataWithResourceName(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithName::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_book_create'));
        $this->assertTrue($operations->has('app_book_update'));
        $this->assertTrue($operations->has('app_book_index'));
        $this->assertTrue($operations->has('app_book_show'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_create');
        $this->assertSame('book', $operation->getResource()->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_update');
        $this->assertSame('book', $operation->getResource()->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_index');
        $this->assertSame('book', $operation->getResource()->getName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_book_show');
        $this->assertSame('book', $operation->getResource()->getName());
    }

    public function testItCreatesResourceMetadataWithResourcePluralName(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithPluralName::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_show'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertSame('books', $operation->getResource()->getPluralName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertSame('books', $operation->getResource()->getPluralName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertSame('books', $operation->getResource()->getPluralName());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $this->assertSame('books', $operation->getResource()->getPluralName());
    }

    public function testItCreatesResourceMetadataWithDefaultResponderOnHttpOperations(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithOperations::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_show'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertSame(Responder::class, $operation->getResponder());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertSame(Responder::class, $operation->getResponder());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertSame(Responder::class, $operation->getResponder());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $this->assertSame(Responder::class, $operation->getResponder());
    }

    public function testItCreatesResourceMetadataWithRoutePrefix(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithRoutePrefix::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_show'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame('/admin', $operation->getRoutePrefix());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertInstanceOf(Update::class, $operation);
        $this->assertSame('/admin', $operation->getRoutePrefix());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('/admin', $operation->getRoutePrefix());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $this->assertInstanceOf(Show::class, $operation);
        $this->assertSame('/admin', $operation->getRoutePrefix());
    }

    public function testItCreatesResourceMetadataWithNormalizationContext(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithNormalizationContext::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_show'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame(['groups' => ['dummy:read']], $operation->getNormalizationContext());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertInstanceOf(Update::class, $operation);
        $this->assertSame(['groups' => ['dummy:read']], $operation->getNormalizationContext());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame(['groups' => ['dummy:read']], $operation->getNormalizationContext());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $this->assertInstanceOf(Show::class, $operation);
        $this->assertSame(['groups' => ['dummy:read']], $operation->getNormalizationContext());
    }

    public function testItCreatesResourceMetadataWithDenormalizationContext(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithDenormalizationContext::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_show'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame(['groups' => ['dummy:write']], $operation->getDenormalizationContext());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertInstanceOf(Update::class, $operation);
        $this->assertSame(['groups' => ['dummy:write']], $operation->getDenormalizationContext());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame(['groups' => ['dummy:write']], $operation->getDenormalizationContext());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $this->assertInstanceOf(Show::class, $operation);
        $this->assertSame(['groups' => ['dummy:write']], $operation->getDenormalizationContext());
    }

    public function testItCreatesResourceMetadataWithDefaultTwigContextFactory(): void
    {
        $this->resourceRegistry
            ->method('get')
            ->with('app.dummy')
            ->willReturn(Metadata::fromAliasAndConfiguration('app.dummy', [
                'driver' => 'dummy_driver',
                'classes' => [
                    'model' => 'App\Dummy',
                    'form' => 'App\Form',
                ],
            ]));

        $metadataCollection = $this->factory->create(DummyResourceWithOperations::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('app.dummy', $resource->getAlias());

        $operations = $resource->getOperations();
        $this->assertInstanceOf(Operations::class, $operations);
        $this->assertCount(4, $operations);
        $this->assertTrue($operations->has('app_dummy_create'));
        $this->assertTrue($operations->has('app_dummy_update'));
        $this->assertTrue($operations->has('app_dummy_index'));
        $this->assertTrue($operations->has('app_dummy_show'));

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_create');
        $this->assertInstanceOf(Create::class, $operation);
        $this->assertSame('sylius.twig.context.factory.default', $operation->getTwigContextFactory());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_update');
        $this->assertInstanceOf(Update::class, $operation);
        $this->assertSame('sylius.twig.context.factory.default', $operation->getTwigContextFactory());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
        $this->assertSame('sylius.twig.context.factory.default', $operation->getTwigContextFactory());

        $operation = $metadataCollection->getOperation('app.dummy', 'app_dummy_show');
        $this->assertInstanceOf(Show::class, $operation);
        $this->assertSame('sylius.twig.context.factory.default', $operation->getTwigContextFactory());
    }
}
