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

namespace spec\Sylius\Bundle\ResourceBundle\Routing;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Routing\AttributesOperationRouteFactory;
use Sylius\Bundle\ResourceBundle\Routing\OperationRouteFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRouteNameFactory;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithOperations;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class AttributesOperationRouteFactorySpec extends ObjectBehavior
{
    function let(RegistryInterface $resourceRegistry): void
    {
        $this->beConstructedWith(
            $resourceRegistry,
            new OperationRouteFactory(),
            new AttributesResourceMetadataCollectionFactory(
                $resourceRegistry->getWrappedObject(),
                new OperationRouteNameFactory(),
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
    ): void {
        $routeCollection = new RouteCollection();

        $metadata->getServiceId('repository')->willReturn('app.repository.dummy');
        $metadata->getClass('form')->willReturn('App\Form');
        $metadata->getClass('model')->willReturn('App\Dummy');
        $resourceRegistry->get('app.dummy')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('dummy');
        $metadata->getPluralName()->willReturn('dummies');

        $this->createRouteForClass($routeCollection, DummyResourceWithOperations::class);

        Assert::count($routeCollection, 4);
        Assert::notNull($routeCollection->get('app_dummy_index'), 'Route "app_dummy_index" not found but it should.');
        Assert::notNull($routeCollection->get('app_dummy_create'), 'Route "app_dummy_create" not found but it should.');
        Assert::notNull($routeCollection->get('app_dummy_update'), 'Route "app_dummy_update" not found but it should.');
        Assert::notNull($routeCollection->get('app_dummy_show'), 'Route "app_dummy_show" not found but it should.');
    }
}
