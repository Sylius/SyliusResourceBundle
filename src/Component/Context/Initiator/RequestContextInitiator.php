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

namespace Sylius\Component\Resource\Context\Initiator;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

final class RequestContextInitiator
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
    ) {
    }

    public function initializeContext(Request $request): Context
    {
        $context = new Context(new RequestOption($request));

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

        return $context->with(new RequestConfigurationOption($configuration));
    }
}
