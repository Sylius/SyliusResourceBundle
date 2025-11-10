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

use Sylius\Bundle\ResourceBundle\Context\Initiator\LegacyRequestContextInitiator;
use Sylius\Resource\Context\Initiator\RequestContextInitiator;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.context.initiator.request_context', RequestContextInitiator::class);

    $services->alias(RequestContextInitiatorInterface::class, 'sylius.context.initiator.request_context');

    $services->set('sylius.context.initiator.legacy_request_context', LegacyRequestContextInitiator::class)
        ->decorate('sylius.context.initiator.request_context')
        ->args([
            service('sylius.resource_registry'),
            service('sylius.resource_controller.request_configuration_factory'),
            service('.inner'),
            service('sylius.expression_language.vars_resolver.metadata'),
        ]);
};
