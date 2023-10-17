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

namespace Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass to bridge the configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle
 *
 * @internal
 */
final class PagerfantaBridgePass implements CompilerPassInterface
{
    public function __construct(private bool $internalUse = false)
    {
    }

    public function process(ContainerBuilder $container): void
    {
        if (false === $this->internalUse) {
            trigger_deprecation(
                'sylius/resource-bundle',
                '1.7',
                'The "%s" class is deprecated. Migrate your Pagerfanta configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle, the configuration bridge will be removed in 2.0.',
                self::class,
            );
        }

        $this->changeViewFactoryClass($container);
        $this->aliasRenamedServices($container);
    }

    private function changeViewFactoryClass(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('white_october_pagerfanta.view_factory.class') || !$container->hasDefinition('pagerfanta.view_factory')) {
            return;
        }

        /** @var string $viewFactoryClass */
        $viewFactoryClass = $container->getParameter('white_october_pagerfanta.view_factory.class');

        $container->getDefinition('pagerfanta.view_factory')
            ->setClass($viewFactoryClass)
        ;
    }

    private function aliasRenamedServices(ContainerBuilder $container): void
    {
        $setDeprecatedMethod = (new \ReflectionClass(Alias::class))->getMethod('setDeprecated');

        if ($container->hasDefinition('pagerfanta.twig_extension')) {
            if (2 === $setDeprecatedMethod->getNumberOfParameters()) {
                $container->setAlias('twig.extension.pagerfanta', 'pagerfanta.twig_extension')
                    ->setDeprecated(true, 'The "%alias_id%" service alias is deprecated since Sylius 1.8, use the "pagerfanta.twig_extension" service ID instead.')
                ;
            } else {
                $container->setAlias('twig.extension.pagerfanta', 'pagerfanta.twig_extension')
                    ->setDeprecated('sylius/resource-bundle', '1.8', 'The "%alias_id%" service alias is deprecated since Sylius 1.8, use the "pagerfanta.twig_extension" service ID instead.')
                ;
            }
        }

        if ($container->hasDefinition('pagerfanta.view_factory')) {
            if (2 === $setDeprecatedMethod->getNumberOfParameters()) {
                $container->setAlias('white_october_pagerfanta.view_factory', 'pagerfanta.view_factory')
                    ->setDeprecated(true, 'The "%alias_id%" service alias is deprecated since Sylius 1.8, use the "pagerfanta.view_factory" service ID instead.')
                ;
            } else {
                $container->setAlias('white_october_pagerfanta.view_factory', 'pagerfanta.view_factory')
                    ->setDeprecated('sylius/resource-bundle', '1.8', 'The "%alias_id%" service alias is deprecated since Sylius 1.8, use the "pagerfanta.view_factory" service ID instead.')
                ;
            }
        }
    }
}

\class_alias(PagerfantaBridgePass::class, \Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PagerfantaBridgePass::class);
