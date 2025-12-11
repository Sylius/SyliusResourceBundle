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

namespace Sylius\Resource\Tests\Doctrine\Common\Metadata\Resource\Factory;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Doctrine\Common\Metadata\Resource\Factory\DoctrineResourceMetadataCollectionFactory;
use Sylius\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class DoctrineResourceMetadataCollectionFactoryTest extends TestCase
{
    private RegistryInterface|MockObject $resourceRegistry;

    private ResourceMetadataCollectionFactoryInterface|MockObject $decorated;

    private DoctrineResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->createMock(RegistryInterface::class);
        $this->decorated = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);

        $this->factory = new DoctrineResourceMetadataCollectionFactory(
            $this->resourceRegistry,
            $this->decorated,
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(DoctrineResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItAddsPersistProcessorToOperationsForResourceWithDoctrineOrmDriver(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $operation = new Create(name: 'app_dummy_create');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);
        $this->resourceRegistry->method('get')->with('app.dummy')->willReturn($metadata);
        $metadata->method('getDriver')->willReturn('doctrine/orm');

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            PersistProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_create')->getProcessor(),
        );
    }

    public function testItAddsPersistProcessorToOperationsForResourceWithDoctrineDbalDriver(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $operation = new Create(name: 'app_dummy_create');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);
        $this->resourceRegistry->method('get')->with('app.dummy')->willReturn($metadata);
        $metadata->method('getDriver')->willReturn('doctrine/dbal');

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            PersistProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_create')->getProcessor(),
        );
    }

    public function testItAddsRemoveProcessorToDeleteOperationsForResourceWithDoctrineDriver(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $operation = new Delete(name: 'app_dummy_delete');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);
        $this->resourceRegistry->method('get')->with('app.dummy')->willReturn($metadata);
        $metadata->method('getDriver')->willReturn('doctrine/orm');

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            RemoveProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_delete')->getProcessor(),
        );
    }
}
