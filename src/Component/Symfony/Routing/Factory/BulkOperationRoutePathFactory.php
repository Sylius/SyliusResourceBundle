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

use Sylius\Component\Resource\Metadata\BulkOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;

class BulkOperationRoutePathFactory implements OperationRoutePathFactoryInterface
{
    public function __construct(private OperationRoutePathFactoryInterface $decorated)
    {
    }

    public function createRoutePath(HttpOperation $operation, string $rootPath): string
    {
        $shortName = $operation->getShortName() ?? '';

        if ($operation instanceof BulkOperationInterface) {
            return sprintf('%s/%s', $rootPath, $shortName);
        }

        return $this->decorated->createRoutePath($operation, $rootPath);
    }
}
