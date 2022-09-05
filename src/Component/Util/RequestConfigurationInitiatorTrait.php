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

namespace Sylius\Component\Resource\Util;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
trait RequestConfigurationInitiatorTrait
{
    private RegistryInterface $resourceRegistry;

    private RequestConfigurationFactoryInterface $requestConfigurationFactory;

    private function initializeConfiguration(Request $request): ?RequestConfiguration
    {
        if ([] === $attributes = $request->attributes->all('_sylius')) {
            return null;
        }

        if (null === ($resource = $attributes['resource'] ?? null)) {
            return null;
        }

        if (str_contains($resource, '.')) {
            $metadata = $this->resourceRegistry->get($resource);
        } else {
            $metadata = $this->resourceRegistry->getByClass($resource);
        }

        return $this->requestConfigurationFactory->create($metadata, $request);
    }
}
