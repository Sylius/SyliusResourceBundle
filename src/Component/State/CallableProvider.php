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
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Doctrine\ORM\State\CollectionProvider;
use Sylius\Component\Resource\Doctrine\ORM\State\ItemProvider;

final class CallableProvider implements ProviderInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    /**
     * @inheritDoc
     */
    public function provide(RequestConfiguration $configuration)
    {
        if (\is_callable($provider = $configuration->getProvider())) {
            return $provider($configuration);
        }

        if (null === $provider) {
            $provider = $this->getDefaultProvider($configuration);
        }

        if (\is_string($provider)) {
            if (!$this->locator->has($provider)) {
                throw new \RuntimeException(sprintf('Provider "%s" not found on operation "%s"', $provider, $configuration->getOperation()));
            }

            /** @var ProviderInterface $provider */
            $provider = $this->locator->get($provider);

            return $provider->provide($configuration);
        }

        throw new \RuntimeException(sprintf('Provider not found on operation "%s"', $configuration->getOperation()));
    }

    private function getDefaultProvider(RequestConfiguration $configuration): ?string
    {
        $driver = $configuration->getMetadata()->getDriver();

        if (SyliusResourceBundle::DRIVER_DOCTRINE_ORM === $driver) {
            if ($configuration->getOperation() === 'index') {
                return CollectionProvider::class;
            }

            return ItemProvider::class;
        }

        return null;
    }
}
