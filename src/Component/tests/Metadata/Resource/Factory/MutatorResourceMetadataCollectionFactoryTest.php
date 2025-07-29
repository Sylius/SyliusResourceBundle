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
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Mutator\OperationMutatorCollection;
use Sylius\Resource\Metadata\Mutator\ResourceMutatorCollection;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\OperationMutatorInterface;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\MutatorResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\ResourceMutatorInterface;

final class MutatorResourceMetadataCollectionFactoryTest extends TestCase
{
    public function testMutateResource(): void
    {
        $decorated = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $resourceClass = \stdClass::class;
        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new ResourceMetadata())->withClass($resourceClass);

        $resourceMutatorCollection = new ResourceMutatorCollection();
        $resourceMutatorCollection->add($resourceClass, new DummyResourceMutator());

        $customResourceMetadataCollectionFactory = new MutatorResourceMetadataCollectionFactory($resourceMutatorCollection, new OperationMutatorCollection(), $decorated);

        $decorated->expects($this->once())->method('create')->with($resourceClass)->willReturn(
            $resourceMetadataCollection,
        );

        $resourceMetadataCollection = $customResourceMetadataCollectionFactory->create($resourceClass);

        $resource = $resourceMetadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame('custom_dummy', $resource->getName());
    }

    public function testMutateOperation(): void
    {
        $decorated = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $resourceClass = \stdClass::class;

        $operations = new Operations();
        $operations->add('app_dummy_index', new HttpOperation());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new ResourceMetadata(alias: 'app.dummy'))->withClass($resourceClass)->withOperations($operations);

        $operationMutatorCollection = new OperationMutatorCollection();
        $operationMutatorCollection->add('app_dummy_index', new DummyOperationMutator());

        $customResourceMetadataCollectionFactory = new MutatorResourceMetadataCollectionFactory(new ResourceMutatorCollection(), $operationMutatorCollection, $decorated);

        $decorated->expects($this->once())->method('create')->with($resourceClass)->willReturn(
            $resourceMetadataCollection,
        );

        $resourceMetadataCollection = $customResourceMetadataCollectionFactory->create($resourceClass);

        $resource = $resourceMetadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertEquals('custom_dummy', $resourceMetadataCollection->getOperation('app.dummy', 'app_dummy_index')->getShortName());
    }
}

final class DummyResourceMutator implements ResourceMutatorInterface
{
    public function __invoke(ResourceMetadata $resource): ResourceMetadata
    {
        return $resource->withName('custom_dummy');
    }
}

final class DummyOperationMutator implements OperationMutatorInterface
{
    public function __invoke(Operation $operation): Operation
    {
        return $operation->withShortName('custom_dummy');
    }
}
