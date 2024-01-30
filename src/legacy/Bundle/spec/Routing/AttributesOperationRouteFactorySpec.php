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

namespace spec\Sylius\Bundle\ResourceBundle\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Routing\AttributesOperationRouteFactory;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithOperations;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactory;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteNameFactory;
use Sylius\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class AttributesOperationRouteFactorySpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $this->beConstructedWith(
            $resourceRegistry,
            new OperationRouteFactory($routePathFactory->getWrappedObject()),
            new AttributesResourceMetadataCollectionFactory(
                $resourceRegistry->getWrappedObject(),
                new OperationRouteNameFactory(),
                'symfony',
            ),
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributesOperationRouteFactory::class);
    }

    function it_creates_routes_with_operations(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $routeCollection = new RouteCollection();

        $metadata->getServiceId('repository')->willReturn('app.repository.dummy');
        $metadata->getClass('form')->willReturn('App\Form');
        $metadata->getClass('model')->willReturn('App\Dummy');
        $metadata->getStateMachineComponent()->willReturn('symfony');
        $resourceRegistry->get('app.dummy')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('dummy');
        $metadata->getPluralName()->willReturn('dummies');

        $routePathFactory->createRoutePath(Argument::type(Index::class), 'dummies')->willReturn('/dummies')->shouldBeCalled();
        $routePathFactory->createRoutePath(Argument::type(Create::class), 'dummies')->willReturn('/dummies/new')->shouldBeCalled();
        $routePathFactory->createRoutePath(Argument::type(Update::class), 'dummies')->willReturn('/dummies/{id}/edit')->shouldBeCalled();
        $routePathFactory->createRoutePath(Argument::type(Show::class), 'dummies')->willReturn('/dummies/{id}')->shouldBeCalled();

        $this->createRouteForClass($routeCollection, DummyResourceWithOperations::class);

        Assert::count($routeCollection, 4);
        Assert::notNull($routeCollection->get('app_dummy_index'), 'Route "app_dummy_index" not found but it should.');
        Assert::notNull($routeCollection->get('app_dummy_create'), 'Route "app_dummy_create" not found but it should.');
        Assert::notNull($routeCollection->get('app_dummy_update'), 'Route "app_dummy_update" not found but it should.');
        Assert::notNull($routeCollection->get('app_dummy_show'), 'Route "app_dummy_show" not found but it should.');
    }
}
