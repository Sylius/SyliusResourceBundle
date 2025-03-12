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

namespace Sylius\Resource\Symfony\Routing\Factory;

use Sylius\Resource\Symfony\Routing\Factory\Resource\ResourceRouteCollectionFactoryInterface;
use Symfony\Component\Routing\RouteCollection;

trigger_deprecation('sylius/resource', '1.13', '"%s" is deprecated, use "%s" instead.', AttributesOperationRouteFactoryInterface::class, ResourceRouteCollectionFactoryInterface::class);

/**
 * @deprecated
 */
interface AttributesOperationRouteFactoryInterface
{
    /** @psalm-param class-string $className */
    public function createRouteForClass(RouteCollection $routeCollection, string $className): void;
}
