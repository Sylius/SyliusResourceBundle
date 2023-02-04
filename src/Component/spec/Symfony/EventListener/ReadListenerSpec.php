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

namespace spec\Sylius\Component\Resource\Symfony\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiator;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\State\ProviderInterface;
use Sylius\Component\Resource\Symfony\EventListener\ReadListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadListenerSpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        MetadataInterface $metadata,
        ProviderInterface $provider,
    ): void {
        $operationInitiator = new HttpOperationInitiator(
            $resourceRegistry->getWrappedObject(),
            $resourceMetadataCollectionFactory->getWrappedObject(),
        );

        $this->beConstructedWith($operationInitiator, new RequestContextInitiator(), $provider);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getAlias()->willReturn('app.dummy');
        $metadata->getClass('model')->willReturn('App\Dummy');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ReadListener::class);
    }

    function it_retrieves_data_and_store_them_to_request(
        RequestEvent $event,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        HttpOperation $operation,
        ProviderInterface $provider,
    ): void {
        $event->getRequest()->willReturn($request);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $provider->provide($operation, Argument::type(Context::class))->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $attributes->set('data', ['foo' => 'fighters'])->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_does_nothing_when_operation_is_a_create_operation(
        RequestEvent $event,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProviderInterface $provider,
    ): void {
        $event->getRequest()->willReturn($request);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create();
        $operations = new Operations();
        $operations->add('app_dummy_create', new Create());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    function it_does_nothing_when_operation_cannot_be_read(
        RequestEvent $event,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProviderInterface $provider,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation->getWrappedObject());

        $operation->canRead()->willReturn(false);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    function it_throws_an_exception_when_no_data_was_found(
        RequestEvent $event,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProviderInterface $provider,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation->getWrappedObject());

        $operation->canRead()->willReturn(true);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $provider->provide($operation, Argument::type(Context::class))->willReturn(null);

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(new NotFoundHttpException('Resource has not been found.'))
            ->during('onKernelRequest', [$event])
        ;
    }
}
