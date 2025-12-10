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

use Sylius\Resource\Metadata\OperationAccessCheckerInterface;
use Sylius\Resource\Symfony\Security\OperationAccessChecker;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.security.operation_access_checker', OperationAccessChecker::class)
        ->args([
            service('sylius.expression_language')->nullOnInvalid(),
            service('security.authentication.trust_resolver')->nullOnInvalid(),
            service('security.role_hierarchy')->nullOnInvalid(),
            service('security.token_storage')->nullOnInvalid(),
            service('security.authorization_checker')->nullOnInvalid(),
        ]);

    $services->alias(OperationAccessCheckerInterface::class, 'sylius.security.operation_access_checker');
};
