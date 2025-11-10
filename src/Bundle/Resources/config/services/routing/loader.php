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

use Sylius\Resource\Symfony\Routing\Loader\ResourceLoader;
use Sylius\Resource\Symfony\Routing\Loader\ResourceLoader as ResourceLoaderInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.symfony.routing.loader.resource', ResourceLoader::class)
        ->args([
            service('sylius.metadata.resource_class_list.factory'),
            service('sylius.routing.resource.route_collection_factory'),
        ])
        ->tag('routing.route_loader');

    $services->alias(ResourceLoaderInterface::class, 'sylius.symfony.routing.loader.resource');
};
