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

    $services->defaults()
        ->public();

    $services->set('sylius.console.command.resource_debug', 'Sylius\Bundle\ResourceBundle\Command\DebugResourceCommand')
        ->args([
            service('sylius.resource_registry'),
            service('sylius.resource_metadata_collection.factory'),
        ])
        ->tag('console.command');

    $services->alias('Sylius\Bundle\ResourceBundle\Command\DebugResourceCommand', 'sylius.console.command.resource_debug');
};
