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

    $services->set('sylius.cache.metadata.resource_collection')
        ->private()
        ->parent('cache.system')
        ->tag('cache.pool');

    $services->alias('sylius.resource_metadata_collection.factory', 'sylius.resource_metadata_collection.factory.attributes');

    $services->alias('Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface', 'sylius.resource_metadata_collection.factory.attributes');

    $services->set('sylius.resource_metadata_collection.factory.attributes', 'Sylius\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory')
        ->args([
            service('sylius.resource_registry'),
            service('sylius.routing.factory.operation_route_name_factory'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.php_file', 'Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory', null, 400)
        ->args([
            service('sylius.resource_registry'),
            service('sylius.routing.factory.operation_route_name_factory'),
            service('sylius.metadata.resource_extractor.php_file'),
            service('.inner'),
        ]);

    $services->set('sylius.metadata.resource.metadata_collection_factory.mutator', 'Sylius\Resource\Metadata\Resource\Factory\MutatorResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory', null, 400)
        ->args([
            service('sylius.metadata.mutator_collection.resource'),
            service('sylius.metadata.mutator_collection.operation'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.state_machine', 'Sylius\Resource\Metadata\Resource\Factory\StateMachineResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory', null, 300)
        ->args([
            service('sylius.resource_registry'),
            service('.inner'),
            '%sylius.state_machine_component.default%',
        ]);

    $services->set('sylius.resource_metadata_collection.factory.doctrine', 'Sylius\Resource\Doctrine\Common\Metadata\Resource\Factory\DoctrineResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory', null, 200)
        ->args([
            service('sylius.resource_registry'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.redirect', 'Sylius\Resource\Metadata\Resource\Factory\RedirectResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory', null, 200)
        ->args([
            service('sylius.routing.factory.operation_route_name_factory'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.vars', 'Sylius\Resource\Metadata\Resource\Factory\VarsResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([service('.inner')]);

    $services->set('sylius.resource_metadata_collection.factory.provider', 'Sylius\Resource\Metadata\Resource\Factory\ProviderResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([service('.inner')]);

    $services->set('sylius.resource_metadata_collection.factory.resource_factory', 'Sylius\Resource\Metadata\Resource\Factory\FactoryResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([
            service('sylius.resource_registry'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.event_short_name', 'Sylius\Resource\Metadata\Resource\Factory\EventShortNameResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([service('.inner')]);

    $services->set('sylius.resource_metadata_collection.factory.templates_dir', 'Sylius\Resource\Metadata\Resource\Factory\TemplatesDirResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([
            service('.inner'),
            '%sylius.resource.settings%',
        ]);

    $services->set('sylius.resource_metadata_collection.factory.cached', 'Sylius\Resource\Metadata\Resource\Factory\CachedResourceMetadataCollectionFactory')
        ->decorate('sylius.resource_metadata_collection.factory', null, -10)
        ->args([
            service('sylius.cache.metadata.resource_collection'),
            service('.inner'),
        ]);
};
