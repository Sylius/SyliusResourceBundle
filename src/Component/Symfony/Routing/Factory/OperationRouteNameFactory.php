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

final class OperationRouteNameFactory implements OperationRouteNameFactoryInterface
{
    public function createRouteName(Operation $operation, ?string $shortName = null): string
    {
        $resource = $operation->getResource();

        if (null === $resource) {
            throw new \RuntimeException(sprintf('No resource was found on the operation "%s"', $operation->getShortName() ?? ''));
        }

        $section = $resource->getSection();
        $sectionPrefix = $section ? $section . '_' : '';

        return sprintf(
            '%s_%s%s_%s',
            $resource->getApplicationName() ?? '',
            $sectionPrefix,
            $resource->getName() ?? '',
            $shortName ?? $operation->getShortName() ?? '',
        );
    }
}
