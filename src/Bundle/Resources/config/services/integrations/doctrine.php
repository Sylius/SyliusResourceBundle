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

    $services->set('Sylius\Bundle\ResourceBundle\Doctrine\ResourceMappingDriverChain')
        ->public()
        ->decorate('doctrine.orm.default_metadata_driver')
        ->args([
            service('Sylius\Bundle\ResourceBundle\Doctrine\ResourceMappingDriverChain.inner'),
            service('sylius.resource_registry'),
        ]);

    $services->alias('sylius_resource.doctrine.mapping_driver_chain', 'Sylius\Bundle\ResourceBundle\Doctrine\ResourceMappingDriverChain');

    $services->set('Sylius\Resource\Doctrine\Common\State\PersistProcessor')
        ->args([service('doctrine')])
        ->tag('sylius.state_processor');

    $services->set('Sylius\Resource\Doctrine\Common\State\RemoveProcessor')
        ->args([service('doctrine')])
        ->tag('sylius.state_processor');
};
