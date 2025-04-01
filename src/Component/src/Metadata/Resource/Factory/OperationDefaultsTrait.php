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

use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Request\State\Responder;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;

/**
 * @internal
 */
trait OperationDefaultsTrait
{
    private function getResourceWithDefaults(
        string $resourceClass,
        ResourceMetadata $resource,
        MetadataInterface $resourceConfiguration,
    ): ResourceMetadata {
        $resource = $resource->withClass($resourceClass);

        if (null === $resource->getAlias()) {
            $resource = $resource->withAlias($resourceConfiguration->getAlias());
        }

        if (null === $resource->getApplicationName()) {
            $resource = $resource->withApplicationName($resourceConfiguration->getApplicationName());
        }

        if (null === $resource->getName()) {
            $resource = $resource->withName($resourceConfiguration->getName());
        }

        return $resource;
    }

    private function getOperationWithDefaults(
        Operation $operation,
        ResourceMetadata $resource,
        OperationRouteNameFactory $operationRouteNameFactory,
        RegistryInterface $resourceRegistry,
    ): array {
        $resourceConfiguration = $resourceRegistry->get($resource->getAlias() ?? '');

        $operation = $operation->withResource($resource);

        if (null === $resource->getName()) {
            $resourceName = $resourceConfiguration->getName();

            $resource = $resource->withName($resourceName);
            $operation = $operation->withResource($resource);
        }

        if (null === $resource->getPluralName()) {
            $resourcePluralName = $resourceConfiguration->getPluralName();

            $resource = $resource->withPluralName($resourcePluralName);
            $operation = $operation->withResource($resource);
        }

        if (null === $operation->getNormalizationContext()) {
            $operation = $operation->withNormalizationContext($resource->getNormalizationContext());
        }

        if (null === $operation->getDenormalizationContext()) {
            $operation = $operation->withDenormalizationContext($resource->getDenormalizationContext());
        }

        if (null === $operation->getValidationContext()) {
            $operation = $operation->withValidationContext($resource->getValidationContext());
        }

        $operation = $operation->withResource($resource);

        if (null === $operation->getRepository()) {
            $operation = $operation->withRepository($resourceConfiguration->getServiceId('repository'));
        }

        if (null === $operation->getFormType()) {
            $formType = $resource->getFormType() ?? $resourceConfiguration->getClass('form');
            $operation = $operation->withFormType($formType);
        }

        $formOptions = $this->buildFormOptions($operation, $resourceConfiguration);
        $operation = $operation->withFormOptions($formOptions);

        if ($operation instanceof HttpOperation) {
            if (null === $operation->getRoutePrefix()) {
                $operation = $operation->withRoutePrefix($resource->getRoutePrefix() ?? null);
            }

            if (null === $operation->getTwigContextFactory()) {
                $operation = $operation->withTwigContextFactory('sylius.twig.context.factory.default');
            }

            if (null === $routeName = $operation->getRouteName()) {
                $routeName = $operationRouteNameFactory->createRouteName($operation);
                $operation = $operation->withRouteName($routeName);
            }

            if (null === $operation->getResponder()) {
                $operation = $operation->withResponder(Responder::class);
            }

            $operation = $operation->withName($routeName);
        }

        $operationName = $operation->getName();

        return [$operationName, $operation];
    }

    private function buildFormOptions(Operation $operation, MetadataInterface $resourceConfiguration): array
    {
        $formOptions = array_merge(
            ['data_class' => $resourceConfiguration->getClass('model')],
            $operation->getFormOptions() ?? [],
        );

        $validationGroups = $operation->getValidationContext()['groups'] ?? null;

        if (null !== $validationGroups) {
            $formOptions = array_merge(['validation_groups' => $validationGroups], $formOptions);
        }

        return $formOptions;
    }
}
