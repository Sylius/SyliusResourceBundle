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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Bundle\ResourceBundle\Routing\CrudRoutesAttributesLoader;
use Sylius\Bundle\ResourceBundle\Routing\ResourceLoader;
use Sylius\Bundle\ResourceBundle\Routing\RouteAttributesFactory;
use Sylius\Bundle\ResourceBundle\Routing\RouteAttributesFactoryInterface;
use Sylius\Bundle\ResourceBundle\Routing\RouteFactory;
use Sylius\Bundle\ResourceBundle\Routing\RoutesAttributesLoader;
use Sylius\Resource\Symfony\Routing\Factory\AttributesOperationRouteFactory;
use Sylius\Resource\Symfony\Routing\Factory\AttributesOperationRouteFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactory;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\BulkOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\CollectionOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\CreateOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\DeleteOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\ShowOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\UpdateOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\RedirectHandler;
use Sylius\Resource\Symfony\Routing\RedirectHandlerInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $container->import('routing/**/**.php');

    $services->defaults()
        ->public();

    $services->set('sylius.routing.loader.resource', ResourceLoader::class)
        ->private()
        ->args([
            service('sylius.resource_registry'),
            inline_service(RouteFactory::class),
            '%kernel.environment%',
            '%sylius.routing_path_bc_layer%',
        ])
        ->tag('routing.loader')
        ->deprecate('sylius/resource', '1.13', 'The "%service_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.symfony.routing.loader.resource" instead.');

    $services->alias(ResourceLoader::class, 'sylius.routing.loader.resource')
        ->private();

    $services->set('sylius.routing.loader.crud_routes_attributes', CrudRoutesAttributesLoader::class)
        ->private()
        ->args([
            '%sylius.resource.mapping%',
            service('sylius.routing.loader.resource'),
        ])
        ->tag('routing.route_loader');

    $services->alias(CrudRoutesAttributesLoader::class, 'sylius.routing.loader.crud_routes_attributes')
        ->private();

    $services->set('sylius.routing.loader.routes_attributes', RoutesAttributesLoader::class)
        ->private()
        ->args([
            '%sylius.resource.mapping%',
            service('sylius.routing.factory.route_attributes'),
            service('sylius.routing.factory.attributes_operation_route'),
        ])
        ->tag('routing.route_loader')
        ->deprecate('sylius/resource', '1.13', 'The "%service_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.symfony.routing.loader.resource" instead.');

    $services->alias(RoutesAttributesLoader::class, 'sylius.routing.loader.routes_attributes')
        ->private();

    $services->set('sylius.routing.factory.operation_route_name_factory', OperationRouteNameFactory::class)
        ->private();

    $services->alias(OperationRouteNameFactoryInterface::class, 'sylius.routing.factory.operation_route_name_factory');

    $services->set('sylius.routing.factory.operation_route_path_factory.default', OperationRoutePathFactory::class)
        ->private();

    $services->alias('sylius.routing.factory.operation_route_path_factory', 'sylius.routing.factory.operation_route_path_factory.default');

    $services->alias(OperationRoutePathFactoryInterface::class, 'sylius.routing.factory.operation_route_path_factory');

    $services->set('sylius.routing.factory.operation_route_path_factory.collection', CollectionOperationRoutePathFactory::class)
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, 60)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.create', CreateOperationRoutePathFactory::class)
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -50)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.bulk_operation', BulkOperationRoutePathFactory::class)
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -40)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.update', UpdateOperationRoutePathFactory::class)
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -30)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.delete', DeleteOperationRoutePathFactory::class)
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -20)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.show', ShowOperationRoutePathFactory::class)
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -10)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.route_attributes', RouteAttributesFactory::class)
        ->private();

    $services->alias(RouteAttributesFactoryInterface::class, 'sylius.routing.factory.route_attributes');

    $services->set('sylius.routing.factory.attributes_operation_route', AttributesOperationRouteFactory::class)
        ->private()
        ->args([
            service('sylius.resource_registry'),
            service('sylius.routing.factory.operation_route'),
            service('sylius.resource_metadata_collection.factory.attributes'),
        ])
        ->deprecate('sylius/resource-bundle', '1.13', 'The "%service_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.routing.resource.route_collection_factory" instead.');

    $services->alias(AttributesOperationRouteFactoryInterface::class, 'sylius.routing.factory.attributes_operation_route')
        ->deprecate('sylius/resource-bundle', '1.13', 'The "%alias_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.routing.resource.route_collection_factory" instead.');

    $services->set('sylius.routing.factory.operation_route', OperationRouteFactory::class)
        ->private()
        ->args([
            service('sylius.routing.factory.operation_route_path_factory'),
            service('sylius.path_segment_name_generator'),
            param('sylius.routing_path_bc_layer'),
        ]);

    $services->alias(OperationRouteFactoryInterface::class, 'sylius.routing.factory.operation_route');

    $services->set('sylius.routing.redirect_handler', RedirectHandler::class)
        ->args([
            service('router'),
            service('sylius.expression_language.argument_parser.routing'),
            service('sylius.routing.factory.operation_route_name_factory'),
            service('sylius.grid.filter_storage')->nullOnInvalid(),
        ]);

    $services->alias(RedirectHandlerInterface::class, 'sylius.routing.redirect_handler');
};
