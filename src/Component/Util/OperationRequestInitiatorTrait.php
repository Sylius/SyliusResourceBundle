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

namespace Sylius\Component\Resource\Util;

use Sylius\Component\Resource\Metadata\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
trait OperationRequestInitiatorTrait
{
    private RegistryInterface $resourceRegistry;

    private ResourceMetadataFactoryInterface $resourceMetadataFactory;

    private function initializeOperation(Request $request): ?Operation
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

        return $this->resourceMetadataFactory->create($metadata->getClass('model'))->getOperation($operationName);
    }
}
