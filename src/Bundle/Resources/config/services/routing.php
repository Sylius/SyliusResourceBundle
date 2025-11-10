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

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $container->import('routing/**/**.php');

    $services->defaults()
        ->public();

    $services->set('sylius.routing.loader.resource', 'Sylius\Bundle\ResourceBundle\Routing\ResourceLoader')
        ->private()
        ->args([
            service('sylius.resource_registry'),
            inline_service('Sylius\Bundle\ResourceBundle\Routing\RouteFactory'),
            '%kernel.environment%',
            '%sylius.routing_path_bc_layer%',
        ])
        ->tag('routing.loader')
        ->deprecate('sylius/resource', '1.13', 'The "%service_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.symfony.routing.loader.resource" instead.');

    $services->alias('Sylius\Bundle\ResourceBundle\Routing\ResourceLoader', 'sylius.routing.loader.resource')
        ->private();

    $services->set('sylius.routing.loader.crud_routes_attributes', 'Sylius\Bundle\ResourceBundle\Routing\CrudRoutesAttributesLoader')
        ->private()
        ->args([
            '%sylius.resource.mapping%',
            service('sylius.routing.loader.resource'),
        ])
        ->tag('routing.route_loader');

    $services->alias('Sylius\Bundle\ResourceBundle\Routing\CrudRoutesAttributesLoader', 'sylius.routing.loader.crud_routes_attributes')
        ->private();

    $services->set('sylius.routing.loader.routes_attributes', 'Sylius\Bundle\ResourceBundle\Routing\RoutesAttributesLoader')
        ->private()
        ->args([
            '%sylius.resource.mapping%',
            service('sylius.routing.factory.route_attributes'),
            service('sylius.routing.factory.attributes_operation_route'),
        ])
        ->tag('routing.route_loader')
        ->deprecate('sylius/resource', '1.13', 'The "%service_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.symfony.routing.loader.resource" instead.');

    $services->alias('Sylius\Bundle\ResourceBundle\Routing\RoutesAttributesLoader', 'sylius.routing.loader.routes_attributes')
        ->private();

    $services->set('sylius.routing.factory.operation_route_name_factory', 'Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory')
        ->private();

    $services->alias('Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactoryInterface', 'sylius.routing.factory.operation_route_name_factory');

    $services->set('sylius.routing.factory.operation_route_path_factory.default', 'Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactory')
        ->private();

    $services->alias('sylius.routing.factory.operation_route_path_factory', 'sylius.routing.factory.operation_route_path_factory.default');

    $services->alias('Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface', 'sylius.routing.factory.operation_route_path_factory');

    $services->set('sylius.routing.factory.operation_route_path_factory.collection', 'Sylius\Resource\Symfony\Routing\Factory\RoutePath\CollectionOperationRoutePathFactory')
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, 60)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.create', 'Sylius\Resource\Symfony\Routing\Factory\RoutePath\CreateOperationRoutePathFactory')
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -50)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.bulk_operation', 'Sylius\Resource\Symfony\Routing\Factory\RoutePath\BulkOperationRoutePathFactory')
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -40)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.update', 'Sylius\Resource\Symfony\Routing\Factory\RoutePath\UpdateOperationRoutePathFactory')
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -30)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.delete', 'Sylius\Resource\Symfony\Routing\Factory\RoutePath\DeleteOperationRoutePathFactory')
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -20)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.operation_route_path_factory.show', 'Sylius\Resource\Symfony\Routing\Factory\RoutePath\ShowOperationRoutePathFactory')
        ->decorate('sylius.routing.factory.operation_route_path_factory.default', null, -10)
        ->args([service('.inner')]);

    $services->set('sylius.routing.factory.route_attributes', 'Sylius\Bundle\ResourceBundle\Routing\RouteAttributesFactory')
        ->private();

    $services->alias('Sylius\Bundle\ResourceBundle\Routing\RouteAttributesFactoryInterface', 'sylius.routing.factory.route_attributes');

    $services->set('sylius.routing.factory.attributes_operation_route', 'Sylius\Resource\Symfony\Routing\Factory\AttributesOperationRouteFactory')
        ->private()
        ->args([
            service('sylius.resource_registry'),
            service('sylius.routing.factory.operation_route'),
            service('sylius.resource_metadata_collection.factory.attributes'),
        ])
        ->deprecate('sylius/resource-bundle', '1.13', 'The "%service_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.routing.resource.route_collection_factory" instead.');

    $services->alias('Sylius\Resource\Symfony\Routing\Factory\AttributesOperationRouteFactoryInterface', 'sylius.routing.factory.attributes_operation_route')
        ->deprecate('sylius/resource-bundle', '1.13', 'The "%alias_id%" service is deprecated since sylius/resource-bundle 1.13 and will be removed in sylius/resource-bundle 2.0. Use "sylius.routing.resource.route_collection_factory" instead.');

    $services->set('sylius.routing.factory.operation_route', 'Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactory')
        ->private()
        ->args([service('sylius.routing.factory.operation_route_path_factory')]);

    $services->alias('Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactoryInterface', 'sylius.routing.factory.operation_route');

    $services->set('sylius.routing.redirect_handler', 'Sylius\Resource\Symfony\Routing\RedirectHandler')
        ->args([
            service('router'),
            service('sylius.expression_language.argument_parser.routing'),
            service('sylius.routing.factory.operation_route_name_factory'),
            service('sylius.grid.filter_storage')->nullOnInvalid(),
        ]);

    $services->alias('Sylius\Resource\Symfony\Routing\RedirectHandlerInterface', 'sylius.routing.redirect_handler');
};
