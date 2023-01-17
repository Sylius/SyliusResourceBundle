<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Resource\Metadata\Operation;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class HttpOperationInitiatorSpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
    ): void {
        $this->beConstructedWith($resourceRegistry, $resourceMetadataCollectionFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HttpOperationInitiator::class);
    }

    function it_initializes_http_operations_from_request(
        Request $request,
        ParameterBag $parameterBag,
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        HttpOperation $operation,
    ): void {
        $request->attributes = $parameterBag;

        $parameterBag->all('_sylius')->willReturn([
            'resource' => 'app.dummy',
        ]);

        $parameterBag->get('_route')->willReturn('app_dummy_index');

        $resourceRegistry->get('app.dummy')->willReturn($metadata);

        $metadata->getClass('model')->willReturn('App\DummyResource');
        $metadata->getAlias()->willReturn('app.dummy');

        $operation->getName()->willReturn('app_dummy_index');

        $operations = new Operations();
        $operations->add('app_dummy_index', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\DummyResource')->willReturn($resourceMetadataCollection);

        $this->initializeOperation($request)->shouldReturn($operation);
    }

    function it_returns_null_when_request_has_no_sylius_options(
        Request $request,
        ParameterBag $parameterBag,
    ): void {
        $request->attributes = $parameterBag;

        $parameterBag->all('_sylius')->willReturn([])->shouldBeCalled();

        $this->initializeOperation($request)->shouldReturn(null);
    }

    function it_returns_null_when_request_has_no_resource_option(
        Request $request,
        ParameterBag $parameterBag,
    ): void {
        $request->attributes = $parameterBag;

        $parameterBag->all('_sylius')->willReturn([
            'foo' => 'bar',
        ])->shouldBeCalled();

        $this->initializeOperation($request)->shouldReturn(null);
    }

    function it_returns_null_when_request_has_no_route(
        Request $request,
        ParameterBag $parameterBag,
    ): void {
        $request->attributes = $parameterBag;

        $parameterBag->all('_sylius')->willReturn([
            'resource' => 'app.dummy',
        ]);

        $parameterBag->get('_route')->willReturn(null)->shouldBeCalled();

        $this->initializeOperation($request)->shouldReturn(null);
    }
}
