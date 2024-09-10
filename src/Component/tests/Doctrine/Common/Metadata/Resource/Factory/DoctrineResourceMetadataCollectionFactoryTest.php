<?php

declare(strict_types=1);

namespace Sylius\Resource\Tests\Doctrine\Common\Metadata\Resource\Factory;

use Prophecy\Prophecy\ObjectProphecy;
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
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class DoctrineResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private RegistryInterface|ObjectProphecy $resourceRegistry;
    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;
    private DoctrineResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->prophesize(RegistryInterface::class);
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);

        $this->factory = new DoctrineResourceMetadataCollectionFactory(
            $this->resourceRegistry->reveal(),
            $this->decorated->reveal()
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(DoctrineResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItAddsPersistProcessorToOperationsForResourceWithDoctrineOrmDriver(): void
    {
        $metadata = $this->prophesize(MetadataInterface::class);
        $operation = new Create(name: 'app_dummy_create');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);
        $this->resourceRegistry->get('app.dummy')->willReturn($metadata->reveal());
        $metadata->getDriver()->willReturn('doctrine/orm');

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            PersistProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_create')->getProcessor()
        );
    }

    public function testItAddsPersistProcessorToOperationsForResourceWithDoctrineDbalDriver(): void
    {
        $metadata = $this->prophesize(MetadataInterface::class);
        $operation = new Create(name: 'app_dummy_create');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);
        $this->resourceRegistry->get('app.dummy')->willReturn($metadata->reveal());
        $metadata->getDriver()->willReturn('doctrine/dbal');

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            PersistProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_create')->getProcessor()
        );
    }

    public function testItAddsRemoveProcessorToDeleteOperationsForResourceWithDoctrineDriver(): void
    {
        $metadata = $this->prophesize(MetadataInterface::class);
        $operation = new Delete(name: 'app_dummy_delete');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);
        $this->resourceRegistry->get('app.dummy')->willReturn($metadata->reveal());
        $metadata->getDriver()->willReturn('doctrine/orm');

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            RemoveProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_delete')->getProcessor()
        );
    }
}
