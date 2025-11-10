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

use Sylius\Resource\State\Processor\FlashProcessor;
use Sylius\Resource\State\Processor\RespondProcessor;
use Sylius\Resource\State\Processor\WriteProcessor;
use Sylius\Resource\Symfony\Serializer\State\SerializeProcessor;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->alias('sylius.state_processor.main', 'sylius.state_processor.respond');

    $services->set('sylius.state_processor.respond', RespondProcessor::class)
        ->args([service('sylius.state_responder')]);

    $services->set('sylius.state_processor.write', WriteProcessor::class)
        ->decorate('sylius.state_processor.main', null, 100)
        ->args([
            service('.inner'),
            service('sylius.state_processor.locator'),
        ]);

    $services->set('sylius.state_processor.serialize', SerializeProcessor::class)
        ->decorate('sylius.state_processor.main', null, 200)
        ->args([
            service('.inner'),
            service('serializer')->nullOnInvalid(),
        ]);

    $services->set('sylius.state_processor.flash', FlashProcessor::class)
        ->decorate('sylius.state_processor.main', null, 300)
        ->args([
            service('.inner'),
            service('sylius.helper.flash'),
        ]);
};
