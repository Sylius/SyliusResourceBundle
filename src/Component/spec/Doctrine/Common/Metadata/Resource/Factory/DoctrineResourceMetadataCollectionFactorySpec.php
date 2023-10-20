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

namespace spec\Sylius\Component\Resource\Doctrine\Common\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Doctrine\Common\Metadata\Resource\Factory\DoctrineResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Component\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\ResourceMetadata;

final class DoctrineResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($resourceRegistry, $decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(DoctrineResourceMetadataCollectionFactory::class);
    }

    function it_adds_persist_processor_to_operations_for_resource_with_doctrine_orm_driver(
        ResourceMetadataCollectionFactoryInterface $decorated,
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.dummy');
        $operation = new Create(name: 'app_dummy_create');
        $resource = $resource->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getDriver()->willReturn('doctrine/orm');

        $result = $this->create('App\Resource');

        $result
            ->getOperation('app.dummy', 'app_dummy_create')
            ->getProcessor()
            ->shouldReturn(PersistProcessor::class)
        ;
    }

    function it_adds_persist_processor_to_operations_for_resource_with_doctrine_dbal_driver(
        ResourceMetadataCollectionFactoryInterface $decorated,
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.dummy');
        $operation = new Create(name: 'app_dummy_create');
        $resource = $resource->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getDriver()->willReturn('doctrine/dbal');

        $result = $this->create('App\Resource');

        $result
            ->getOperation('app.dummy', 'app_dummy_create')
            ->getProcessor()
            ->shouldReturn(PersistProcessor::class)
        ;
    }

    function it_adds_remove_processor_to_delete_operations_for_resource_with_doctrine_driver(
        ResourceMetadataCollectionFactoryInterface $decorated,
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.dummy');
        $operation = new Delete(name: 'app_dummy_delete');
        $resource = $resource->withOperations(new Operations([$operation]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resource]);

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getDriver()->willReturn('doctrine/orm');

        $result = $this->create('App\Resource');

        $result
            ->getOperation('app.dummy', 'app_dummy_delete')
            ->getProcessor()
            ->shouldReturn(RemoveProcessor::class)
        ;
    }
}
