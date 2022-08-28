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
        $attributes = $this->filterOperationAttributes($attributes);

        foreach ($attributes as $attribute) {
            if (!is_a($operationClassName = $attribute->getName(), Operation::class, true)) {
                continue;
            }

            $operation = $this->operationFactory->create($operationClassName, $attribute->getArguments());

            $this->addRouteForOperation($routeCollection, $operation);
        }
    }

    /** @param \ReflectionAttribute[] $attributes */
    private function filterOperationAttributes(array $attributes): array
    {
        return array_filter($attributes, function (\ReflectionAttribute $attribute): bool {
            return is_a($attribute->getName(), Operation::class, true);
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
