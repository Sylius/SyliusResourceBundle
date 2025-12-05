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

namespace Sylius\Resource\Metadata\Resource\Factory;

use Sylius\Resource\Exception\LogicException;
use Sylius\Resource\Metadata\Inflector\Inflector;
use Sylius\Resource\Metadata\Inflector\InflectorInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

/**
 * @experimental
 */
final class PluralNameResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    private InflectorInterface $inflector;

    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $decorated,
        ?InflectorInterface $inflector = null,
        private readonly bool $routingBcLayerEnabled = true,
        private readonly ?RegistryInterface $resourceRegistry = null,
    ) {
        $this->inflector = $inflector ?? new Inflector();
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceCollectionMetadata = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceCollectionMetadata->getIterator() as $i => $resource) {
            $resourceConfiguration = $this->resourceRegistry?->get($resource->getAlias() ?? '');

            $resourceCollectionMetadata[$i] = $this->addDefaults($resource, $resourceConfiguration);
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(ResourceMetadata $resource, ?MetadataInterface $resourceConfiguration = null): ResourceMetadata
    {
        if (null !== $resource->getPluralName()) {
            return $resource;
        }

        if ($this->routingBcLayerEnabled) {
            $pluralName = $resourceConfiguration?->getPluralName()
                ?? throw new LogicException(sprintf(
                    'Routing Bc-Layer is enabled, but the resource registry is not passed as constructor arguments of "%s" class.',
                    self::class,
                ))
            ;

            return $resource->withPluralName($pluralName);
        }

        /**
         * Resource name has already been configured.
         *
         * @see OperationDefaultsTrait
         *
         * @var string $resourceName
         */
        $resourceName = $resource->getName();

        return $resource->withPluralName($this->inflector->pluralize($resourceName));
    }
}
