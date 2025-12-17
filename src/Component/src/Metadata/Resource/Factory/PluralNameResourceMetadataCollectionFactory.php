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
use Sylius\Resource\Metadata\Inflector\InflectorInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

/**
 * @experimental
 */
final class PluralNameResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $decorated,
        private readonly InflectorInterface $inflector,
        private readonly bool $routingBcLayerEnabled = true,
        private readonly ?RegistryInterface $resourceRegistry = null,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceCollectionMetadata = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceCollectionMetadata->getIterator() as $i => $resource) {
            $resourceCollectionMetadata[$i] = $this->addDefaults($resource);
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(ResourceMetadata $resource): ResourceMetadata
    {
        if (null !== $resource->getPluralName()) {
            return $resource;
        }

        if ($this->routingBcLayerEnabled) {
            if (null === $this->resourceRegistry) {
                throw new LogicException(sprintf(
                    'Routing Bc-Layer is enabled, but the resource registry is not passed as constructor arguments of "%s" class.',
                    self::class,
                ));
            }

            $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias() ?? '');

            return $resource->withPluralName($resourceConfiguration->getPluralName());
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
