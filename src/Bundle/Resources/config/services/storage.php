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

use Sylius\Bundle\ResourceBundle\Storage\CookieStorage;
use Sylius\Bundle\ResourceBundle\Storage\CookieStorage as CookieStorageInterface;
use Sylius\Bundle\ResourceBundle\Storage\SessionStorage;
use Sylius\Bundle\ResourceBundle\Storage\SessionStorage as SessionStorageInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius.storage.session', SessionStorage::class)
        ->args([service('request_stack')]);

    $services->alias(SessionStorageInterface::class, 'sylius.storage.session');

    $services->set('sylius.storage.cookie', CookieStorage::class)
        ->tag('kernel.event_subscriber');

    $services->alias(CookieStorageInterface::class, 'sylius.storage.cookie');
};
