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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Container extension to bridge the configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle
 *
 * @internal
 */
final class PagerfantaExtension extends Extension implements PrependExtensionInterface
{
    public function __construct(private bool $internalUse = false)
    {
    }

    public function getAlias(): string
    {
        return 'white_october_pagerfanta';
    }

    public function getConfiguration(array $config, ContainerBuilder $container): PagerfantaConfiguration
    {
        return new PagerfantaConfiguration();
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        if (false === $this->internalUse) {
            trigger_deprecation(
                'sylius/resource-bundle',
                '1.7',
                'The "%s" class is deprecated. Migrate your Pagerfanta configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle, the configuration bridge will be removed in 2.0.',
                self::class,
            );
        }

        $config = $this->processConfiguration($this->getConfiguration($configs, $container), $configs);

        $container->setParameter('white_october_pagerfanta.default_view', $config['default_view']);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $container->getExtensionConfig($this->getAlias()));

        $container->prependExtensionConfig('babdev_pagerfanta', $config);
    }
}
