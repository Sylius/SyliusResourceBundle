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

    $services->alias('sylius.metadata.resource_class_list.factory', 'sylius.metadata.resource_class_list.factory.attributes');

    $services->alias('Sylius\Resource\Metadata\Resource\Factory\ResourceClassListFactoryInterface', 'sylius.metadata.resource_class_list.factory');

    $services->set('sylius.metadata.resource_class_list.factory.attributes', 'Sylius\Resource\Metadata\Resource\Factory\AttributesResourceClassListFactory')
        ->args(['%sylius.resource.mapping%']);

    $services->alias('Sylius\Resource\Metadata\Resource\Factory\AttributesResourceClassListFactory', 'sylius.metadata.resource_class_list.factory.attributes');

    $services->set('sylius.metadata.resource_class_list.factory.php_file', 'Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceClassListFactory')
        ->decorate('sylius.metadata.resource_class_list.factory', null, 100)
        ->args([
            service('sylius.metadata.resource_extractor.php_file'),
            service('.inner'),
        ]);

    $services->alias('Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceClassListFactory', 'sylius.metadata.resource_class_list.factory.php_file');
};
