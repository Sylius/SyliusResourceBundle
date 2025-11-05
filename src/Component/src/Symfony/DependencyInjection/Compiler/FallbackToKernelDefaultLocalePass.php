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

namespace Sylius\Resource\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 */
final class FallbackToKernelDefaultLocalePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (
            $container->hasParameter('locale') ||
            !$container->hasParameter('kernel.default_locale')
        ) {
            return;
        }

        /** @var string $locale */
        $locale = $container->getParameter('kernel.default_locale');

        $this->replaceLocaleInFlashHelper($container, $locale);
        $this->replaceLocaleProvider($container, $locale);
    }

    private function replaceLocaleInFlashHelper(ContainerBuilder $container, string $locale): void
    {
        if (!$container->hasDefinition('sylius.resource_controller.flash_helper')) {
            return;
        }

        $flashHelper = $container->getDefinition('sylius.resource_controller.flash_helper');
        $flashHelper->replaceArgument(2, $locale);
    }

    private function replaceLocaleProvider(ContainerBuilder $container, string $locale): void
    {
        if (!$container->hasDefinition('sylius.translation_locale_provider.immutable')) {
            return;
        }

        $localeProvider = $container->getDefinition('sylius.translation_locale_provider.immutable');
        $localeProvider->replaceArgument(0, [$locale]);
        $localeProvider->replaceArgument(1, $locale);
    }
}
