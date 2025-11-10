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

    $services->set('sylius.storage.session', 'Sylius\Bundle\ResourceBundle\Storage\SessionStorage')
        ->args([service('request_stack')]);

    $services->alias('Sylius\Bundle\ResourceBundle\Storage\SessionStorage', 'sylius.storage.session');

    $services->set('sylius.storage.cookie', 'Sylius\Bundle\ResourceBundle\Storage\CookieStorage')
        ->tag('kernel.event_subscriber');

    $services->alias('Sylius\Bundle\ResourceBundle\Storage\CookieStorage', 'sylius.storage.cookie');
};
