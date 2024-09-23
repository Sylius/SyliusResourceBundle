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

namespace Sylius\Resource\Tests\Metadata\Operation;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class HttpOperationInitiatorTest extends TestCase
{
    use ProphecyTrait;

    private RegistryInterface|ObjectProphecy $resourceRegistry;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $resourceMetadataCollectionFactory;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->prophesize(RegistryInterface::class);
        $this->resourceMetadataCollectionFactory = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
    }

    public function testItIsInitializable(): void
    {
        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry->reveal(),
            $this->resourceMetadataCollectionFactory->reveal(),
        );

        $this->assertInstanceOf(HttpOperationInitiator::class, $initiator);
    }

    public function testItInitializesHttpOperationsFromRequest(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $metadata = $this->prophesize(MetadataInterface::class);
        $operation = $this->prophesize(HttpOperation::class);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_index');
        $attributes->all('_sylius')->willReturn([
            'resource' => 'app.dummy',
        ]);
        $attributes->set('_sylius', ['resource' => 'app.dummy', 'resource_class' => 'App\DummyResource'])->shouldBeCalled();

        $this->resourceRegistry->get('app.dummy')->willReturn($metadata);

        $metadata->getClass('model')->willReturn('App\DummyResource');
        $metadata->getAlias()->willReturn('app.dummy');

        $operation->getName()->willReturn('app_dummy_index');

        $operations = new Operations();
        $operations->add('app_dummy_index', $operation->reveal());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new ResourceMetadata(alias: 'app.dummy'))->withOperations($operations);

        $this->resourceMetadataCollectionFactory->create('App\DummyResource')->willReturn($resourceMetadataCollection);

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry->reveal(),
            $this->resourceMetadataCollectionFactory->reveal(),
        );

        $this->assertSame($operation->reveal(), $initiator->initializeOperation($request->reveal()));
    }

    public function testItReturnsNullWhenRequestHasNoSyliusOptions(): void
    {
        $request = $this->prophesize(Request::class);
        $parameterBag = $this->prophesize(ParameterBag::class);

        $request->attributes = $parameterBag;

        $parameterBag->get('_route')->willReturn('app_dummy_index');
        $parameterBag->all('_sylius')->willReturn([])->shouldBeCalled();

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry->reveal(),
            $this->resourceMetadataCollectionFactory->reveal(),
        );

        $this->assertNull($initiator->initializeOperation($request->reveal()));
    }

    public function testItReturnsNullWhenRequestHasNoResourceOption(): void
    {
        $request = $this->prophesize(Request::class);
        $parameterBag = $this->prophesize(ParameterBag::class);

        $request->attributes = $parameterBag;

        $parameterBag->get('_route')->willReturn('app_dummy_index');
        $parameterBag->all('_sylius')->willReturn([
            'foo' => 'bar',
        ])->shouldBeCalled();

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry->reveal(),
            $this->resourceMetadataCollectionFactory->reveal(),
        );

        $this->assertNull($initiator->initializeOperation($request->reveal()));
    }

    public function testItReturnsNullWhenRequestHasNoRoute(): void
    {
        $request = $this->prophesize(Request::class);
        $parameterBag = $this->prophesize(ParameterBag::class);

        $request->attributes = $parameterBag;

        $parameterBag->all('_sylius')->willReturn([
            'resource' => 'app.dummy',
        ]);

        $parameterBag->get('_route')->willReturn(null)->shouldBeCalled();

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry->reveal(),
            $this->resourceMetadataCollectionFactory->reveal(),
        );

        $this->assertNull($initiator->initializeOperation($request->reveal()));
    }
}
