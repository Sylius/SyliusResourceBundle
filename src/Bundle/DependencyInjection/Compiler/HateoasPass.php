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

use Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class HateoasPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array $bundles */
        $bundles = $container->getParameter('kernel.bundles');

        if (in_array(BazingaHateoasBundle::class, $bundles, true)) {
            return;
        }

        $container->removeDefinition('sylius.resource_controller.pagerfanta_representation_factory');
    }
}
