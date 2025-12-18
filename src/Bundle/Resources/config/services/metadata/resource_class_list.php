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

use Sylius\Resource\Metadata\Resource\Factory\AttributesResourceClassListFactory;
use Sylius\Resource\Metadata\Resource\Factory\AttributesResourceClassListFactory as AttributesResourceClassListFactoryInterface;
use Sylius\Resource\Metadata\Resource\Factory\CachedResourceClassListFactory;
use Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceClassListFactory;
use Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceClassListFactory as PhpFileResourceClassListFactoryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceClassListFactoryInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.cache.metadata.resource_class_list')
        ->private()
        ->parent('cache.system')
        ->tag('cache.pool');

    $services->alias('sylius.metadata.resource_class_list.factory', 'sylius.metadata.resource_class_list.factory.attributes');

    $services->alias(ResourceClassListFactoryInterface::class, 'sylius.metadata.resource_class_list.factory');

    $services->set('sylius.metadata.resource_class_list.factory.attributes', AttributesResourceClassListFactory::class)
        ->args(['%sylius.resource.mapping%']);

    $services->alias(AttributesResourceClassListFactoryInterface::class, 'sylius.metadata.resource_class_list.factory.attributes');

    $services->set('sylius.metadata.resource_class_list.cached', CachedResourceClassListFactory::class)
        ->decorate('sylius.metadata.resource_class_list.factory', null, -10)
        ->args([
            service('sylius.cache.metadata.resource_class_list'),
            service('.inner'),
        ]);

    $services->set('sylius.metadata.resource_class_list.factory.php_file', PhpFileResourceClassListFactory::class)
        ->decorate('sylius.metadata.resource_class_list.factory', null, 100)
        ->args([
            service('sylius.metadata.resource_extractor.php_file'),
            service('.inner'),
        ]);

    $services->alias(PhpFileResourceClassListFactoryInterface::class, 'sylius.metadata.resource_class_list.factory.php_file');
};
