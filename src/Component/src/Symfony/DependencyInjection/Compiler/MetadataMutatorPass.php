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
use Symfony\Component\DependencyInjection\Reference;

final class MetadataMutatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->processResourceMutators($container);
        $this->processOperationMutators($container);
    }

    public function processResourceMutators(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sylius.metadata.mutator_collection.resource')) {
            return;
        }

        $definition = $container->getDefinition('sylius.metadata.mutator_collection.resource');

        $mutators = $container->findTaggedServiceIds('sylius.resource_mutator');

        foreach ($mutators as $id => $tags) {
            foreach ($tags as $tag) {
                $definition->addMethodCall('add', [
                    $tag['resourceClass'],
                    new Reference($id),
                ]);
            }
        }
    }

    private function processOperationMutators(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sylius.metadata.mutator_collection.operation')) {
            return;
        }

        $definition = $container->getDefinition('sylius.metadata.mutator_collection.operation');

        $mutators = $container->findTaggedServiceIds('sylius.operation_mutator');

        foreach ($mutators as $id => $tags) {
            foreach ($tags as $tag) {
                $definition->addMethodCall('add', [
                    $tag['operationName'],
                    new Reference($id),
                ]);
            }
        }
    }
}
