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
        if (null === $varsResolver) {
            trigger_deprecation(
                'sylius/resource-bundle',
                '1.14',
                'Not passing an instance of "%s" as the third constructor argument for "%s" is deprecated and will not be supported in 2.0.',
                VarsResolverInterface::class,
                self::class,
            );
        }
    }

    public function initializeOperation(Request $request): ?HttpOperation
    {
        /** @var string|null $operationName */
        $operationName = $request->attributes->get('_route');
        $syliusOptions = $attributes = $request->attributes->all('_sylius');

        /** @var string|class-string|null $resource */
        $resource = $attributes['resource'] ?? null;

        if (
            [] === $syliusOptions ||
            null === $resource ||
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
        $operationVars = $operation->getVars();
        $resolvedOperationVars = $operationVars !== null ? $this->resolveVars($operationVars) : null;

        $resourceVars = $operation->getResource()?->getVars();
        $resolvedResourceVars = $resourceVars !== null ? $this->resolveVars($resourceVars) : null;

        if (null === $resolvedOperationVars && null === $resolvedResourceVars) {
            return $operation;
        }

        $mergedVars = array_merge($resolvedResourceVars ?? [], $resolvedOperationVars ?? []);

        return $operation->withVars($mergedVars);
    }

    private function resolveVars(array $vars): array
    {
        if (null === $this->varsResolver) {
            return $vars;
        }

        return $this->varsResolver->resolve($vars);
    }
}
