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

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Component\Resource\Metadata\Factory\OperationFactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class OperationAttributesRouteFactory implements OperationAttributesRouteFactoryInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private OperationFactoryInterface $operationFactory,
        private OperationRouteFactoryInterface $operationRouteFactory,
    ) {
    }

    public function createRouteForClass(RouteCollection $routeCollection, string $className): void
    {
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className));
    }

    /** @param \ReflectionAttribute[] $attributes */
    private function createRouteForAttributes(RouteCollection $routeCollection, array $attributes): void
    {
        $resourceArguments = $this->getResourceArguments($attributes);
        $attributes = $this->filterAttributes($attributes, Operation::class);

        foreach ($attributes as $attribute) {
            if (!is_a($operationClassName = $attribute->getName(), Operation::class, true)) {
                continue;
            }

            $arguments = array_merge($attribute->getArguments(), $resourceArguments);
            $operation = $this->operationFactory->create($operationClassName, $arguments);

            $this->addRouteForOperation($routeCollection, $operation);
        }
    }

    private function getResourceArguments($attributes): array
    {
        $resourceAttributes = $this->filterAttributes($attributes, Resource::class);

        foreach ($resourceAttributes as $resourceAttribute) {
            $arguments = $resourceAttribute->getArguments();

            $arguments['resource'] = $arguments['alias'];
            unset($arguments['alias']);

            return $arguments;
        }

        return [];
    }

    /** @param \ReflectionAttribute[] $attributes */
    private function filterAttributes(array $attributes, $className): array
    {
        return array_filter($attributes, function (\ReflectionAttribute $attribute) use ($className): bool {
            return is_a($attribute->getName(), $className, true);
        });
    }

    private function addRouteForOperation(RouteCollection $routeCollection, Operation $operation): void
    {
        Assert::notNull($operation->getResource(), 'Impossible to get default route name without resource. Please define a resource.');

        $metadata = $this->resourceRegistry->get($operation->getResource());

        $routeName = $operation->getName() ?? $this->getDefaultRouteName($metadata, $operation);

        $route = $this->createRoute($metadata, $operation);
        $routeCollection->add($routeName, $route);
    }

    private function createRoute(MetadataInterface $metadata, Operation $operation): Route
    {
        return $this->operationRouteFactory->create($metadata, $operation);
    }

    private function getDefaultRouteName(MetadataInterface $metadata, Operation $operation): string
    {
        if (null !== $section = $operation->getSection()) {
            $section = '_' . $section;
        }

        return sprintf('%s%s_%s_%s', $metadata->getApplicationName(), $section ?? '', $metadata->getName(), $operation->getAction());
    }
}
