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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\Factory\StateMachineResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;

interface MetadataWithStateMachineInterface extends MetadataInterface
{
    public function getStateMachineComponent(): ?string;
}

final class StateMachineResourceMetadataCollectionFactoryTest extends TestCase
{
    private RegistryInterface|MockObject $resourceRegistry;

    private ResourceMetadataCollectionFactoryInterface|MockObject $decorated;

    private StateMachineResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->createMock(RegistryInterface::class);
        $this->decorated = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new StateMachineResourceMetadataCollectionFactory($this->resourceRegistry, $this->decorated, null);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(StateMachineResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItSetsTheDefaultStateMachineComponentFromSettings(): void
    {
        $this->factory = new StateMachineResourceMetadataCollectionFactory($this->resourceRegistry, $this->decorated, 'symfony');

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->createMock(MetadataWithStateMachineInterface::class);
        $resourceConfiguration->method('getStateMachineComponent')->willReturn(null);
        $this->resourceRegistry->method('get')->with('app.book')->willReturn($resourceConfiguration);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertEquals('symfony', $create->getStateMachineComponent());
    }

    public function testItSetsTheDefaultStateMachineComponentFromResourceConfiguration(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->createMock(MetadataWithStateMachineInterface::class);
        $resourceConfiguration->method('getStateMachineComponent')->willReturn('symfony');
        $this->resourceRegistry->method('get')->with('app.book')->willReturn($resourceConfiguration);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertEquals('symfony', $create->getStateMachineComponent());
    }

    public function testItSetsTheConfiguredStateMachineComponentFromOperation(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(name: 'app_book_create', stateMachineComponent: 'winzou'))->withResource($resource);
        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->createMock(MetadataWithStateMachineInterface::class);
        $resourceConfiguration->method('getStateMachineComponent')->willReturn('symfony');
        $this->resourceRegistry->method('get')->with('app.book')->willReturn($resourceConfiguration);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertEquals('winzou', $create->getStateMachineComponent());
    }

    public function testItConfiguresApplyStateMachineTransitionProcessorIfOperationHasATransition(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(name: 'app_book_create', stateMachineTransition: 'publish'))->withResource($resource);
        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->createMock(MetadataWithStateMachineInterface::class);
        $resourceConfiguration->method('getStateMachineComponent')->willReturn('symfony');
        $this->resourceRegistry->method('get')->with('app.book')->willReturn($resourceConfiguration);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertEquals(ApplyStateMachineTransitionProcessor::class, $create->getProcessor());
    }
}
