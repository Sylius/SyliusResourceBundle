<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Component\Resource\Metadata\Factory\OperationFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\RouteCollection;

interface OperationAttributesRouteFactoryInterface
{
    /** @psalm-param class-string $className */
    public function createRouteForClass(RouteCollection $routeCollection, string $className): void;
}
