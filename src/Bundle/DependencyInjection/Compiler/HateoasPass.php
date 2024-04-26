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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Sylius\Resource\Hateas\Configuration\Metadata\Driver\ExtensionDriver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class HateoasPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (
            $container->hasDefinition('annotation_reader') ||
            !$container->hasDefinition('hateoas.configuration.metadata.annotation_driver')
        ) {
            return;
        }

        $container->removeDefinition('hateoas.configuration.metadata.annotation_driver');

        $this->decorateExtensionDriver($container);
    }

    private function decorateExtensionDriver(ContainerBuilder $container): void
    {
        $container
            ->register('sylius.hateoas_configuration.metadata.extension_driver', ExtensionDriver::class)
            ->setDecoratedService('hateoas.configuration.metadata.extension_driver')
            ->setPublic(false);
    }
}
