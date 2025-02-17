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

namespace Sylius\Resource\Tests\Doctrine\ORM\Metadata\Resource\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Resource\Doctrine\ORM\Metadata\Resource\Factory\DoctrineORMResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class DoctrineORMResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ManagerRegistry|ObjectProphecy $managerRegistry;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    private DoctrineORMResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->prophesize(ManagerRegistry::class);
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);

        $this->factory = new DoctrineORMResourceMetadataCollectionFactory(
            $this->managerRegistry->reveal(),
            $this->decorated->reveal(),
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(DoctrineORMResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItAddsPersistProcessorToOperationsForResourceManagedByDoctrineOrm(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $operation = new Create(name: 'app_dummy_create');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]))
            ->withClass('App\Dummy')
        ;

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);
        $this->managerRegistry->getManagerForClass('App\Dummy')->willReturn($entityManager);

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            PersistProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_create')->getProcessor(),
        );
    }

    public function testItAddsRemoveProcessorToDeleteOperationsForResourceManagedByDoctrineOrm(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $operation = new Delete(name: 'app_dummy_delete');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]))
            ->withClass('App\Dummy')
        ;

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);
        $this->managerRegistry->getManagerForClass('App\Dummy')->willReturn($entityManager);

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            RemoveProcessor::class,
            $result->getOperation('app.dummy', 'app_dummy_delete')->getProcessor(),
        );
    }

    public function testItDoesNothingWhenResourceIsNotManagedByDoctrineOrm(): void
    {
        $operation = new Create(name: 'app_dummy_create');
        $resource = (new ResourceMetadata(alias: 'app.dummy'))
            ->withOperations(new Operations([$operation]))
            ->withClass('App\Dummy')
        ;

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);
        $this->managerRegistry->getManagerForClass('App\Dummy')->willReturn(null);

        $result = $this->factory->create('App\Resource');

        $this->assertEquals(
            null,
            $result->getOperation('app.dummy', 'app_dummy_create')->getProcessor(),
        );
    }
}
