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

use Sylius\Bundle\ResourceBundle\DataTransformer\ChainDataTransformerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ChainDataTransformerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ChainDataTransformerInterface::class);
        $taggedServices = $container->findTaggedServiceIds('sylius.data_transformer');

        foreach ($taggedServices as $id => $tags) {
            // add the data transformer service to the ChainDataTransformer service
            $definition->addMethodCall('addDataTransformer', [new Reference($id)]);
        }
    }
}
