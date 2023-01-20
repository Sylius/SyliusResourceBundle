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

namespace Sylius\Component\Resource\State;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;

final class CallableProvider implements ProviderInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        $provider = $operation->getProvider()
        
        if (is_null($provider)) {        
        
            return null;
        }

        if (\is_callable($provider)) {
            return $provider($operation, $context);
        }

        if (!$this->locator->has($provider)) {
            throw new \RuntimeException(sprintf('Provider "%s" not found on operation "%s"', $provider, $operation->getName() ?? ''));
        }

        /** @var ProviderInterface $providerInstance */
        $providerInstance = $this->locator->get($provider);

        return $providerInstance->provide($operation, $context);
    }
}
