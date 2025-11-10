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

use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcher;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandler;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.dispatcher.operation', OperationEventDispatcher::class)
        ->args([service('event_dispatcher')]);

    $services->alias(OperationEventDispatcherInterface::class, 'sylius.dispatcher.operation');

    $services->set('sylius.event_handler.operation', OperationEventHandler::class)
        ->args([
            service('sylius.routing.redirect_handler'),
            service('sylius.helper.flash'),
        ]);

    $services->alias(OperationEventHandlerInterface::class, 'sylius.event_handler.operation');
};
