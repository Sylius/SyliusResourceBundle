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

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * Resolves given target entities with container parameters.
 * Usable only with *doctrine/orm* driver.
 */
final class DoctrineTargetEntitiesResolverPass implements CompilerPassInterface
{
    private TargetEntitiesResolverInterface $targetEntitiesResolver;

    public function __construct(TargetEntitiesResolverInterface $targetEntitiesResolver)
    {
        $this->targetEntitiesResolver = $targetEntitiesResolver;
    }

    public function process(ContainerBuilder $container): void
    {
        try {
            /** @var array $resources */
            $resources = $container->getParameter('sylius.resources');
            $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        } catch (InvalidArgumentException $exception) {
            return;
        }

        $interfaces = $this->targetEntitiesResolver->resolve($resources);
        foreach ($interfaces as $interface => $model) {
            $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [$interface, $model, []]);
        }

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_listener')) {
            $resolveTargetEntityListener->addTag('doctrine.event_listener', ['event' => 'loadClassMetadata']);
        }
    }
}
