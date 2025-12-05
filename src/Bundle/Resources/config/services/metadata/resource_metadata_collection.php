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

use Sylius\Resource\Doctrine\Common\Metadata\Resource\Factory\DoctrineResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\CachedResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\EventShortNameResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\FactoryResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\MutatorResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\PluralNameResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ProviderResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\RedirectResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\Factory\StateMachineResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\TemplatesDirResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\VarsResourceMetadataCollectionFactory;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.cache.metadata.resource_collection')
        ->private()
        ->parent('cache.system')
        ->tag('cache.pool');

    $services->alias('sylius.resource_metadata_collection.factory', 'sylius.resource_metadata_collection.factory.attributes');

    $services->alias(ResourceMetadataCollectionFactoryInterface::class, 'sylius.resource_metadata_collection.factory.attributes');

    $services->set('sylius.resource_metadata_collection.factory.attributes', AttributesResourceMetadataCollectionFactory::class)
        ->args([
            service('sylius.resource_registry'),
            service('sylius.routing.factory.operation_route_name_factory'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.php_file', PhpFileResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory', null, 400)
        ->args([
            service('sylius.resource_registry'),
            service('sylius.routing.factory.operation_route_name_factory'),
            service('sylius.metadata.resource_extractor.php_file'),
            service('.inner'),
        ]);

    $services->set('sylius.metadata.resource.metadata_collection_factory.mutator', MutatorResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory', null, 400)
        ->args([
            service('sylius.metadata.mutator_collection.resource'),
            service('sylius.metadata.mutator_collection.operation'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.plural_name', PluralNameResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory', null, 300)
        ->args([
            service('.inner'),
            service('sylius.metadata.inflector')->nullOnInvalid(),
            param('sylius.routing_path_bc_layer'),
            service('sylius.resource_registry'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.state_machine', StateMachineResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory', null, 300)
        ->args([
            service('sylius.resource_registry'),
            service('.inner'),
            '%sylius.state_machine_component.default%',
        ]);

    $services->set('sylius.resource_metadata_collection.factory.doctrine', DoctrineResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory', null, 200)
        ->args([
            service('sylius.resource_registry'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.redirect', RedirectResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory', null, 200)
        ->args([
            service('sylius.routing.factory.operation_route_name_factory'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.vars', VarsResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([service('.inner')]);

    $services->set('sylius.resource_metadata_collection.factory.provider', ProviderResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([service('.inner')]);

    $services->set('sylius.resource_metadata_collection.factory.resource_factory', FactoryResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([
            service('sylius.resource_registry'),
            service('.inner'),
        ]);

    $services->set('sylius.resource_metadata_collection.factory.event_short_name', EventShortNameResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([service('.inner')]);

    $services->set('sylius.resource_metadata_collection.factory.templates_dir', TemplatesDirResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory')
        ->args([
            service('.inner'),
            '%sylius.resource.settings%',
        ]);

    $services->set('sylius.resource_metadata_collection.factory.cached', CachedResourceMetadataCollectionFactory::class)
        ->decorate('sylius.resource_metadata_collection.factory', null, -10)
        ->args([
            service('sylius.cache.metadata.resource_collection'),
            service('.inner'),
        ]);
};
