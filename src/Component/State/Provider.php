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

namespace Sylius\Component\Resource\State;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Resource\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class Provider implements ProviderInterface
{
    public function __construct(
        private ContainerInterface $locator,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        $provider = $operation->getProvider();

        if (null === $provider) {
            return null;
        }

        if (\is_callable($provider)) {
            return $provider($operation, $context);
        }

        if (!$this->locator->has($provider)) {
            throw new \RuntimeException(sprintf('Provider "%s" not found on operation "%s"', $provider, $operation->getName() ?? ''));
        }

        $providerInstance = $this->locator->get($provider);
        Assert::isInstanceOf($providerInstance, ProviderInterface::class);

        return $providerInstance->provide($operation, $context);
    }
}
