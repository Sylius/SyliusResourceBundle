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
    $container->import('processor/write.php');

    $services->alias('sylius.state_processor.main', 'sylius.state_processor.respond');

    $services->set('sylius.state_processor.respond', 'Sylius\Resource\State\Processor\RespondProcessor')
        ->args([service('sylius.state_responder')]);

    $services->set('sylius.state_processor.write', 'Sylius\Resource\State\Processor\WriteProcessor')
        ->decorate('sylius.state_processor.main', null, 100)
        ->args([
            service('.inner'),
            service('sylius.state_processor.locator'),
        ]);

    $services->set('sylius.state_processor.serialize', 'Sylius\Resource\Symfony\Serializer\State\SerializeProcessor')
        ->decorate('sylius.state_processor.main', null, 200)
        ->args([
            service('.inner'),
            service('serializer')->nullOnInvalid(),
        ]);

    $services->set('sylius.state_processor.flash', 'Sylius\Resource\State\Processor\FlashProcessor')
        ->decorate('sylius.state_processor.main', null, 300)
        ->args([
            service('.inner'),
            service('sylius.helper.flash'),
        ]);
};
