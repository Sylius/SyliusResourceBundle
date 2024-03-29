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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

abstract class PrioritizedCompositeServicePass implements CompilerPassInterface
{
    private string $serviceId;

    private string $compositeId;

    private string $tagName;

    private string $methodName;

    public function __construct(string $serviceId, string $compositeId, string $tagName, string $methodName)
    {
        $this->serviceId = $serviceId;
        $this->compositeId = $compositeId;
        $this->tagName = $tagName;
        $this->methodName = $methodName;
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->compositeId)) {
            return;
        }

        $this->injectTaggedServicesIntoComposite($container);
        $this->addAliasForCompositeIfServiceDoesNotExist($container);
    }

    private function injectTaggedServicesIntoComposite(ContainerBuilder $container): void
    {
        $contextDefinition = $container->findDefinition($this->compositeId);

        $taggedServices = $container->findTaggedServiceIds($this->tagName);
        foreach ($taggedServices as $id => $tags) {
            $this->addMethodCalls($contextDefinition, $id, $tags);
        }
    }

    private function addAliasForCompositeIfServiceDoesNotExist(ContainerBuilder $container): void
    {
        if ($container->has($this->serviceId)) {
            return;
        }

        $container->setAlias($this->serviceId, $this->compositeId)->setPublic(true);
    }

    private function addMethodCalls(Definition $contextDefinition, string $id, array $tags): void
    {
        foreach ($tags as $attributes) {
            $this->addMethodCall($contextDefinition, $id, $attributes);
        }
    }

    private function addMethodCall(Definition $contextDefinition, string $id, array $attributes): void
    {
        $contextDefinition->addMethodCall($this->methodName, [new Reference($id), $attributes['priority'] ?? 0]);
    }
}
