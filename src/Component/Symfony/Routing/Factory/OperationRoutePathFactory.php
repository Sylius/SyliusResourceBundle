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

namespace Sylius\Component\Resource\Symfony\Routing\Factory;

use Sylius\Component\Resource\Metadata\Operation;

final class OperationRoutePathFactory implements OperationRoutePathFactoryInterface
{
    public function createRoutePath(Operation $operation, string $rootPath): string
    {
        throw new \InvalidArgumentException(sprintf('Impossible to get a default route path for operation "%s". Please define a path.', $operation->getName() ?? ''));
    }
}
