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

    $services->set('sylius.symfony.routing.loader.resource', 'Sylius\Resource\Symfony\Routing\Loader\ResourceLoader')
        ->args([
            service('sylius.metadata.resource_class_list.factory'),
            service('sylius.routing.resource.route_collection_factory'),
        ])
        ->tag('routing.route_loader');

    $services->alias('Sylius\Resource\Symfony\Routing\Loader\ResourceLoader', 'sylius.symfony.routing.loader.resource');
};
