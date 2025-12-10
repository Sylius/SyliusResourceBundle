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

use Sylius\Resource\Symfony\Routing\Factory\Resource\ResourceRouteCollectionFactory;
use Sylius\Resource\Symfony\Routing\Factory\Resource\ResourceRouteCollectionFactoryInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.routing.resource.route_collection_factory', ResourceRouteCollectionFactory::class)
        ->args([
            service('sylius.routing.factory.operation_route'),
            service('sylius.resource_metadata_collection.factory'),
            service('sylius.resource_registry'),
        ]);

    $services->alias(ResourceRouteCollectionFactoryInterface::class, 'sylius.routing.resource.route_collection_factory');
};
