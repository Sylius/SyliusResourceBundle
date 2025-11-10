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

    $services->set('sylius.state_processor.locator', 'Sylius\Resource\State\Processor')
        ->args([tagged_locator('sylius.state_processor')]);

    $services->set('sylius.state_processor.dispatch_pre_write_event', 'Sylius\Resource\Symfony\EventDispatcher\State\DispatchPreWriteEventProcessor')
        ->decorate('sylius.state_processor.locator', null, 200)
        ->args([
            service('.inner'),
            service('sylius.dispatcher.operation'),
            service('sylius.event_handler.operation'),
        ]);

    $services->set('sylius.state_processor.dispatch_post_write_event', 'Sylius\Resource\Symfony\EventDispatcher\State\DispatchPostWriteEventProcessor')
        ->decorate('sylius.state_processor.locator', null, 200)
        ->args([
            service('.inner'),
            service('sylius.dispatcher.operation'),
            service('sylius.event_handler.operation'),
        ]);

    $services->set('sylius.state_processor.bulk_aware', 'Sylius\Resource\State\Processor\BulkAwareProcessor')
        ->decorate('sylius.state_processor.locator', null, 100)
        ->args([service('.inner')]);
};
