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

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        if (\is_callable($provider = $operation->getProvider())) {
            return $provider($operation, $context);
        }

        if (\is_string($provider)) {
            if (!$this->locator->has($provider)) {
                throw new \RuntimeException(sprintf('Provider "%s" not found on operation "%s"', $provider, $operation->getName()));
            }

            /** @var ProviderInterface $provider */
            $provider = $this->locator->get($provider);

            return $provider->provide($operation, $context);
        }

        throw new \RuntimeException(sprintf('Provider not found on operation "%s"', $operation->getName()));
    }
}