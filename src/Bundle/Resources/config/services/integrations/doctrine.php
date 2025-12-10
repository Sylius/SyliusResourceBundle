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

use Sylius\Bundle\ResourceBundle\Doctrine\ResourceMappingDriverChain;
use Sylius\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Resource\Doctrine\Common\State\RemoveProcessor;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set(ResourceMappingDriverChain::class)
        ->public()
        ->decorate('doctrine.orm.default_metadata_driver')
        ->args([
            service(ResourceMappingDriverChain::class . '.inner'),
            service('sylius.resource_registry'),
        ]);

    $services->alias('sylius_resource.doctrine.mapping_driver_chain', ResourceMappingDriverChain::class);

    $services->set(PersistProcessor::class)
        ->args([service('doctrine')])
        ->tag('sylius.state_processor');

    $services->set(RemoveProcessor::class)
        ->args([service('doctrine')])
        ->tag('sylius.state_processor');
};
