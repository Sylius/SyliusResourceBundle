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

use Sylius\Resource\Symfony\Session\Flash\FlashHelper;
use Sylius\Resource\Symfony\Session\Flash\FlashHelperInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.helper.flash', FlashHelper::class)
        ->args([service('translator')]);

    $services->alias(FlashHelperInterface::class, 'sylius.helper.flash');
};
