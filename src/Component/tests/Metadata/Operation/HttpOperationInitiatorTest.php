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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\ExpressionLanguage\VarsResolverInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class HttpOperationInitiatorTest extends TestCase
{
    private RegistryInterface|MockObject $resourceRegistry;

    private ResourceMetadataCollectionFactoryInterface|MockObject $resourceMetadataCollectionFactory;

    private VarsResolverInterface|MockObject $varsResolver;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->createMock(RegistryInterface::class);
        $this->resourceMetadataCollectionFactory = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $this->varsResolver = $this->createMock(VarsResolverInterface::class);
    }

    public function testItIsInitializable(): void
    {
        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry,
            $this->resourceMetadataCollectionFactory,
            $this->varsResolver,
        );

        $this->assertInstanceOf(HttpOperationInitiator::class, $initiator);
    }

    public function testItInitializesHttpOperationsFromRequest(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $metadata = $this->createMock(MetadataInterface::class);
        $operation = $this->createMock(HttpOperation::class);

        $request->attributes = $attributes;

        $attributes->method('get')->with('_route')->willReturn('app_dummy_index');
        $attributes->method('all')->with('_sylius')->willReturn([
            'resource' => 'app.dummy',
        ]);
        $attributes->expects($this->once())->method('set')->with('_sylius', ['resource' => 'app.dummy', 'resource_class' => 'App\DummyResource']);

        $this->resourceRegistry->method('get')->with('app.dummy')->willReturn($metadata);

        $metadata->method('getClass')->with('model')->willReturn('App\DummyResource');
        $metadata->method('getAlias')->willReturn('app.dummy');

        $operation->method('getName')->willReturn('app_dummy_index');
        $operation->method('getVars')->willReturn(null);
        $operation->method('getResource')->willReturn(null);

        $operations = new Operations();
        $operations->add('app_dummy_index', $operation);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new ResourceMetadata(alias: 'app.dummy'))->withOperations($operations);

        $this->resourceMetadataCollectionFactory->method('create')->with('App\DummyResource')->willReturn($resourceMetadataCollection);

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry,
            $this->resourceMetadataCollectionFactory,
        );

        $this->assertSame($operation, $initiator->initializeOperation($request));
    }

    public function testItResolvesOperationVars(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $metadata = $this->createMock(MetadataInterface::class);
        $operation = new Index(name: 'app_dummy_index', vars: ['product' => '@=get_current_product()']);

        $request->attributes = $attributes;

        $attributes->method('get')->with('_route')->willReturn('app_dummy_index');
        $attributes->method('all')->with('_sylius')->willReturn([
            'resource' => 'app.dummy',
        ]);
        $attributes->expects($this->once())->method('set')->with('_sylius', ['resource' => 'app.dummy', 'resource_class' => 'App\DummyResource']);

        $this->resourceRegistry->method('get')->with('app.dummy')->willReturn($metadata);

        $metadata->method('getClass')->with('model')->willReturn('App\DummyResource');
        $metadata->method('getAlias')->willReturn('app.dummy');

        $operations = new Operations();
        $operations->add('app_dummy_index', $operation);

        $product = new \stdClass();
        $this->varsResolver->expects($this->once())->method('resolve')->with(['product' => '@=get_current_product()'])->willReturn(['product' => $product]);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new ResourceMetadata(alias: 'app.dummy'))->withOperations($operations);

        $this->resourceMetadataCollectionFactory->method('create')->with('App\DummyResource')->willReturn($resourceMetadataCollection);

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry,
            $this->resourceMetadataCollectionFactory,
            $this->varsResolver,
        );

        $result = $initiator->initializeOperation($request);
        $this->assertNotNull($result);
        $this->assertSame(['product' => $product], $result->getVars());
    }

    public function testItReturnsNullWhenRequestHasNoSyliusOptions(): void
    {
        $request = $this->createMock(Request::class);
        $parameterBag = $this->createMock(ParameterBag::class);

        $request->attributes = $parameterBag;

        $parameterBag->method('get')->with('_route')->willReturn('app_dummy_index');
        $parameterBag->expects($this->once())->method('all')->with('_sylius')->willReturn([]);

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry,
            $this->resourceMetadataCollectionFactory,
        );

        $this->assertNull($initiator->initializeOperation($request));
    }

    public function testItReturnsNullWhenRequestHasNoResourceOption(): void
    {
        $request = $this->createMock(Request::class);
        $parameterBag = $this->createMock(ParameterBag::class);

        $request->attributes = $parameterBag;

        $parameterBag->method('get')->with('_route')->willReturn('app_dummy_index');
        $parameterBag->expects($this->once())->method('all')->with('_sylius')->willReturn([
            'foo' => 'bar',
        ]);

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry,
            $this->resourceMetadataCollectionFactory,
        );

        $this->assertNull($initiator->initializeOperation($request));
    }

    public function testItReturnsNullWhenRequestHasNoRoute(): void
    {
        $request = $this->createMock(Request::class);
        $parameterBag = $this->createMock(ParameterBag::class);

        $request->attributes = $parameterBag;

        $parameterBag->method('all')->with('_sylius')->willReturn([
            'resource' => 'app.dummy',
        ]);

        $parameterBag->expects($this->once())->method('get')->with('_route')->willReturn(null);

        $initiator = new HttpOperationInitiator(
            $this->resourceRegistry,
            $this->resourceMetadataCollectionFactory,
        );

        $this->assertNull($initiator->initializeOperation($request));
    }
}
