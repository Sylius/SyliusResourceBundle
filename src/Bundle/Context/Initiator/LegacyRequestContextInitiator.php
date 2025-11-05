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
use Sylius\Resource\Symfony\ExpressionLanguage\VarsResolverInterface;
use Symfony\Component\HttpFoundation\Request;

final class LegacyRequestContextInitiator implements RequestContextInitiatorInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private RequestContextInitiatorInterface $decorated,
        private ?VarsResolverInterface $varsResolver = null,
    ) {
        if (null === $varsResolver) {
            trigger_deprecation(
                'sylius/resource-bundle',
                '1.14',
                'Not passing an instance of "%s" as the fourth constructor argument for "%s" is deprecated and will not be supported in 2.0.',
                VarsResolverInterface::class,
                self::class,
            );
        }
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
        $configurationVars = $this->resolveVars($configuration->getVars());

        $configuration->getParameters()->set('vars', $configurationVars);

        return $context->with(new MetadataOption($metadata), new RequestConfigurationOption($configuration));
    }

    private function resolveVars(array $vars): array
    {
        if (null === $this->varsResolver) {
            return $vars;
        }

        return $this->varsResolver->resolve($vars);
    }
}
