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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Exception\LogicException;
use Sylius\Resource\Metadata\Inflector\Inflector;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\PluralNameResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

#[CoversClass(PluralNameResourceMetadataCollectionFactory::class)]
final class PluralNameResourceMetadataCollectionFactoryTest extends TestCase
{
    private ResourceMetadataCollectionFactoryInterface&MockObject $decorated;

    private RegistryInterface&MockObject $registry;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $this->registry = $this->createMock(RegistryInterface::class);
    }

    public function testItConfiguresDefaultPluralNameOnResourcesWithRoutingBcLayerEnabled(): void
    {
        $factory = new PluralNameResourceMetadataCollectionFactory(decorated: $this->decorated, inflector: new Inflector(), resourceRegistry: $this->registry, routingBcLayerEnabled: true);
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);
        $this->registry->method('get')->with('app.book')->willReturn(Metadata::fromAliasAndConfiguration('app.book', ['driver' => 'doctrine/orm']));

        $resourceMetadataCollection = $factory->create('App\Resource');
        $resourceOutput = $resourceMetadataCollection[0];

        $this->assertInstanceOf(ResourceMetadata::class, $resourceOutput);
        $this->assertSame('books', $resourceOutput->getPluralName());
    }

    public function testItConfiguresDefaultPluralNameOnResourcesWithRoutingBcLayerDisabled(): void
    {
        $factory = new PluralNameResourceMetadataCollectionFactory(decorated: $this->decorated, inflector: new Inflector(), routingBcLayerEnabled: false);
        $resource = new ResourceMetadata(name: 'book');

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $factory->create('App\Resource');
        $resourceOutput = $resourceMetadataCollection[0];

        $this->assertInstanceOf(ResourceMetadata::class, $resourceOutput);
        $this->assertSame('books', $resourceOutput->getPluralName());
    }

    public function testItThrowAnExceptionWithRoutingBcLayerEnabledWhenResourceRegistryIsNotPassedAsConstructorArguments(): void
    {
        $factory = new PluralNameResourceMetadataCollectionFactory(decorated: $this->decorated, inflector: new Inflector(), routingBcLayerEnabled: true);
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf(
            'Routing Bc-Layer is enabled, but the resource registry is not passed as constructor arguments of "%s" class.',
            PluralNameResourceMetadataCollectionFactory::class,
        ));

        $factory->create('App\Resource');
    }
}
