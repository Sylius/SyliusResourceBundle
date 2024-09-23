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
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\Factory\StateMachineResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;

final class StateMachineResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private RegistryInterface|ObjectProphecy $resourceRegistry;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    private StateMachineResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->prophesize(RegistryInterface::class);
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new StateMachineResourceMetadataCollectionFactory($this->resourceRegistry->reveal(), $this->decorated->reveal(), null);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(StateMachineResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItSetsTheDefaultStateMachineComponentFromSettings(): void
    {
        $this->factory = new StateMachineResourceMetadataCollectionFactory($this->resourceRegistry->reveal(), $this->decorated->reveal(), 'symfony');

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->prophesize(MetadataInterface::class);
        $resourceConfiguration->getStateMachineComponent()->willReturn(null);
        $this->resourceRegistry->get('app.book')->willReturn($resourceConfiguration->reveal());

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

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->prophesize(MetadataInterface::class);
        $resourceConfiguration->getStateMachineComponent()->willReturn('symfony');
        $this->resourceRegistry->get('app.book')->willReturn($resourceConfiguration->reveal());

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

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->prophesize(MetadataInterface::class);
        $resourceConfiguration->getStateMachineComponent()->willReturn('symfony');
        $this->resourceRegistry->get('app.book')->willReturn($resourceConfiguration->reveal());

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

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceConfiguration = $this->prophesize(MetadataInterface::class);
        $resourceConfiguration->getStateMachineComponent()->willReturn('symfony');
        $this->resourceRegistry->get('app.book')->willReturn($resourceConfiguration->reveal());

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertEquals(ApplyStateMachineTransitionProcessor::class, $create->getProcessor());
    }
}
