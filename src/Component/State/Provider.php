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
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\ShowOperationInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class Provider implements ProviderInterface
{
    public function __construct(
        private ContainerInterface $locator,
        private OperationEventDispatcherInterface $operationEventDispatcher,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        $provider = $operation->getProvider();

        if (null === $provider) {
            return null;
        }

        if (
            $operation instanceof CollectionOperationInterface ||
            $operation instanceof ShowOperationInterface
        ) {
            $this->operationEventDispatcher->dispatch(null, $operation, $context);
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
