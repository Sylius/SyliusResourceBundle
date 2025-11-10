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

    $services->set('sylius.dispatcher.operation', 'Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcher')
        ->args([service('event_dispatcher')]);

    $services->alias('Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface', 'sylius.dispatcher.operation');

    $services->set('sylius.event_handler.operation', 'Sylius\Resource\Symfony\EventDispatcher\OperationEventHandler')
        ->args([
            service('sylius.routing.redirect_handler'),
            service('sylius.helper.flash'),
        ]);

    $services->alias('Sylius\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface', 'sylius.event_handler.operation');
};
