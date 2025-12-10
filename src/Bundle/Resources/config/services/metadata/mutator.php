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

use Sylius\Resource\Metadata\Mutator\OperationMutatorCollection;
use Sylius\Resource\Metadata\Mutator\ResourceMutatorCollection;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.metadata.mutator_collection.resource', ResourceMutatorCollection::class)
        ->private();

    $services->set('sylius.metadata.mutator_collection.operation', OperationMutatorCollection::class)
        ->private();
};
