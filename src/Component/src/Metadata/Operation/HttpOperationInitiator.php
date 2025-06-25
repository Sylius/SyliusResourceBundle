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

namespace Sylius\Resource\Metadata\Operation;

use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Symfony\ExpressionLanguage\VarsResolverInterface;
use Symfony\Component\HttpFoundation\Request;

final class HttpOperationInitiator implements HttpOperationInitiatorInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private ?VarsResolverInterface $varsResolver = null,
    ) {
    }

    public function initializeOperation(Request $request): ?HttpOperation
    {
        /** @var string|null $operationName */
        $operationName = $request->attributes->get('_route');
        $syliusOptions = $attributes = $request->attributes->all('_sylius');

        if (
            [] === $syliusOptions ||
            null === ($resource = $attributes['resource'] ?? null) ||
            null === $operationName
        ) {
            return null;
        }

        if (str_contains($resource, '.')) {
            $metadata = $this->resourceRegistry->get($resource);
        } else {
            $metadata = $this->resourceRegistry->getByClass($resource);
        }

        $syliusOptions['resource_class'] = $metadata->getClass('model');
        $request->attributes->set('_sylius', $syliusOptions);

        /** @var HttpOperation $operation */
        $operation = $this->resourceMetadataCollectionFactory->create($metadata->getClass('model'))
            ->getOperation($metadata->getAlias(), $operationName)
        ;

        return $this->getOperationWithVars($operation);
    }

    private function getOperationWithVars(HttpOperation $operation): HttpOperation
    {
        $operationVars = null !== $operation->getVars() ? $this->resolveVars($operation->getVars()) : null;

        if (null !== $operationVars) {
            $operation = $operation->withVars($operationVars);
        }

        $resource = $operation->getResource();
        $resourceVars = $resource?->getVars();

        if (null === $resourceVars) {
            return $operation;
        }

        return $operation->withVars(\array_merge($this->resolveVars($resourceVars), $operationVars ?? []));
    }

    private function resolveVars(array $vars): array
    {
        if (null === $this->varsResolver) {
            return $vars;
        }

        return $this->varsResolver->resolve($vars);
    }
}
