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

namespace Sylius\Resource\Symfony\Routing\Factory\Resource;

use Symfony\Component\Routing\RouteCollection;

/**
 * @experimental
 */
interface ResourceRouteCollectionFactoryInterface
{
    /** @param class-string $className */
    public function createRouteCollectionForClass(string $className): RouteCollection;
}
