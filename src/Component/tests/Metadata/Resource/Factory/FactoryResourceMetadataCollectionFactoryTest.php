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
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\FactoryResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class FactoryResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private RegistryInterface|ObjectProphecy $resourceRegistry;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    private FactoryResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->prophesize(RegistryInterface::class);
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new FactoryResourceMetadataCollectionFactory($this->resourceRegistry->reveal(), $this->decorated->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(FactoryResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItConfiguresFactoryIfOperationImplementsTheInterface(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);
        $this->resourceRegistry->get('app.book')->willReturn($this->prophesize(MetadataInterface::class)->reveal());

        $resourceConfiguration = $this->prophesize(MetadataInterface::class);
        $resourceConfiguration->getDriver()->willReturn('doctrine/orm');
        $resourceConfiguration->getServiceId('factory')->willReturn('app.factory.book');

        $this->resourceRegistry->get('app.book')->willReturn($resourceConfiguration->reveal());

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertSame('app.factory.book', $create->getFactory());
    }
}
