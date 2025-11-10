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

use Sylius\Resource\Symfony\Request\RepositoryArgumentResolver;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.repository_argument_resolver.request', RepositoryArgumentResolver::class);
};
