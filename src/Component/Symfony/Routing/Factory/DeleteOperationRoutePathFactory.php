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

use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;

class DeleteOperationRoutePathFactory implements OperationRoutePathFactoryInterface
{
    public function __construct(private OperationRoutePathFactoryInterface $decorated)
    {
    }

    public function createRoutePath(HttpOperation $operation, string $rootPath): string
    {
        $shortName = $operation->getShortName();
        $identifier = $operation->getResource()?->getIdentifier() ?? 'id';

        if ($operation instanceof DeleteOperationInterface) {
            $path = match ($shortName) {
                'delete' => '',
                default => '/' . $shortName,
            };

            return sprintf('%s/{%s}%s', $rootPath, $identifier, $path);
        }

        return $this->decorated->createRoutePath($operation, $rootPath);
    }
}
