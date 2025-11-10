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

use Sylius\Resource\State\Processor;
use Sylius\Resource\State\Processor\BulkAwareProcessor;
use Sylius\Resource\Symfony\EventDispatcher\State\DispatchPostWriteEventProcessor;
use Sylius\Resource\Symfony\EventDispatcher\State\DispatchPreWriteEventProcessor;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.state_processor.locator', Processor::class)
        ->args([tagged_locator('sylius.state_processor')]);

    $services->set('sylius.state_processor.dispatch_pre_write_event', DispatchPreWriteEventProcessor::class)
        ->decorate('sylius.state_processor.locator', null, 200)
        ->args([
            service('.inner'),
            service('sylius.dispatcher.operation'),
            service('sylius.event_handler.operation'),
        ]);

    $services->set('sylius.state_processor.dispatch_post_write_event', DispatchPostWriteEventProcessor::class)
        ->decorate('sylius.state_processor.locator', null, 200)
        ->args([
            service('.inner'),
            service('sylius.dispatcher.operation'),
            service('sylius.event_handler.operation'),
        ]);

    $services->set('sylius.state_processor.bulk_aware', BulkAwareProcessor::class)
        ->decorate('sylius.state_processor.locator', null, 100)
        ->args([service('.inner')]);
};
