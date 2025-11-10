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

use Sylius\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.resource_metadata_operation.initiator.http_operation', HttpOperationInitiator::class)
        ->args([
            service('sylius.resource_registry'),
            service('sylius.resource_metadata_collection.factory'),
            service('sylius.expression_language.vars_resolver.metadata'),
        ]);

    $services->alias(HttpOperationInitiatorInterface::class, 'sylius.resource_metadata_operation.initiator.http_operation');
};
