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

namespace Sylius\Bundle\ResourceBundle\Context\Initiator;

use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

final class LegacyRequestContextInitiator implements RequestContextInitiatorInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private RequestContextInitiatorInterface $decorated,
    ) {
    }

    public function initializeContext(Request $request): Context
    {
        $context = $this->decorated->initializeContext($request);

        if ([] === $attributes = $request->attributes->all('_sylius')) {
            return $context;
        }

        if (null === ($resource = $attributes['resource'] ?? null)) {
            return $context;
        }

        if (str_contains($resource, '.')) {
            $metadata = $this->resourceRegistry->get($resource);
        } else {
            $metadata = $this->resourceRegistry->getByClass($resource);
        }

        $configuration = $this->requestConfigurationFactory->create($metadata, $request);

        return $context->with(new MetadataOption($metadata), new RequestConfigurationOption($configuration));
    }
}
