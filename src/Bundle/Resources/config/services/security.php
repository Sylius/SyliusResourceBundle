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

    $services->set('sylius.security.operation_access_checker', 'Sylius\Resource\Symfony\Security\OperationAccessChecker')
        ->args([
            service('sylius.expression_language')->nullOnInvalid(),
            service('security.authentication.trust_resolver')->nullOnInvalid(),
            service('security.role_hierarchy')->nullOnInvalid(),
            service('security.token_storage')->nullOnInvalid(),
            service('security.authorization_checker')->nullOnInvalid(),
        ]);

    $services->alias('Sylius\Resource\Metadata\OperationAccessCheckerInterface', 'sylius.security.operation_access_checker');
};
