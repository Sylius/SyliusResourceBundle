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

namespace Sylius\Resource\Symfony\Routing\Factory\RoutePath;

use Sylius\Resource\Metadata\CollectionOperationInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\PathSegmentNameGeneratorInterface;

/**
 * @experimental
 */
final class CollectionOperationRoutePathFactory implements OperationRoutePathFactoryInterface
{
    public function __construct(
        private OperationRoutePathFactoryInterface $decorated,
        private PathSegmentNameGeneratorInterface $pathSegmentNameGenerator,
    ) {
    }

    public function createRoutePath(HttpOperation $operation, string $rootPath): string
    {
        $shortName = $operation->getShortName();

        if ($operation instanceof CollectionOperationInterface) {
            $path = match ($shortName) {
                'index', 'get_collection' => '',
                default => '/' . $this->pathSegmentNameGenerator->getSegmentName($shortName ?? '', false),
            };

            return sprintf('%s%s', $rootPath, $path);
        }

        return $this->decorated->createRoutePath($operation, $rootPath);
    }
}
