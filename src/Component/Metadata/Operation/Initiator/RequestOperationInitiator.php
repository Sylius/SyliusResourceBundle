<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Metadata\Operation\Initiator;

use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

final class RequestOperationInitiator
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
    ) {
    }

    public function initializeOperation(Request $request): ?Operation
    {
        $attributes = $request->attributes->all('_sylius');

        if (
            [] === $attributes ||
            null === ($resource = $attributes['resource'] ?? null) ||
            null === ($operationName = $attributes['operation'] ?? null)
        ) {
            return null;
        }

        if (str_contains($resource, '.')) {
            $metadata = $this->resourceRegistry->get($resource);
        } else {
            $metadata = $this->resourceRegistry->getByClass($resource);
        }

        return $this->resourceMetadataCollectionFactory->create($metadata->getClass('model'))
            ->getOperation($metadata->getAlias(), $operationName)
        ;
    }
}
