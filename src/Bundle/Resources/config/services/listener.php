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

use Negotiation\Negotiator;
use Sylius\Resource\Symfony\EventListener\AddFormatListener;
use Sylius\Resource\Symfony\Validator\EventListener\ValidationExceptionListener;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.negotiator', Negotiator::class);

    $services->set('sylius.listener.add_format', AddFormatListener::class)
        ->args([
            service('sylius.resource_metadata_operation.initiator.http_operation'),
            service('sylius.negotiator'),
        ])
        ->tag('kernel.event_listener', ['event' => 'kernel.request', 'priority' => 28]);

    $services->set('sylius.listener.exception.validation', ValidationExceptionListener::class)
        ->args([service('serializer')->nullOnInvalid()])
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException'])
        ->lazy();
};
