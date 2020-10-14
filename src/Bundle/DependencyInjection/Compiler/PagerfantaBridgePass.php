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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.8. Migrate your Pagerfanta configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle, the configuration bridge will be removed in Sylius 2.0.', PagerfantaBridgePass::class), \E_USER_DEPRECATED);

/**
 * Compiler pass to bridge the configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle
 *
 * @internal
 */
final class PagerfantaBridgePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->changeViewFactoryClass($container);
        $this->aliasRenamedServices($container);
    }

    private function changeViewFactoryClass(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('white_october_pagerfanta.view_factory.class') || !$container->hasDefinition('pagerfanta.view_factory')) {
            return;
        }

        $container->getDefinition('pagerfanta.view_factory')
            ->setClass($container->getParameter('white_october_pagerfanta.view_factory.class'));
    }

    private function aliasRenamedServices(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('pagerfanta.twig_extension')) {
            $container->setAlias('twig.extension.pagerfanta', 'pagerfanta.twig_extension')
                ->setDeprecated(true, 'The "%alias_id%" service alias is deprecated since Sylius 1.8, use the "pagerfanta.twig_extension" service ID instead.');
        }

        if ($container->hasDefinition('pagerfanta.view_factory')) {
            $container->setAlias('white_october_pagerfanta.view_factory', 'pagerfanta.view_factory')
                ->setDeprecated(true, 'The "%alias_id%" service alias is deprecated since Sylius 1.8, use the "pagerfanta.view_factory" service ID instead.');
        }
    }
}
