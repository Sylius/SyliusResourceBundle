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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterResourceStateMachinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('sylius.resources')) {
            return;
        }

        /** @var array $resources */
        $resources = $container->getParameter('sylius.resources');

        foreach ($resources as $alias => $configuration) {
            [$applicationName, $resourceName] = explode('.', $alias, 2);
            $stateMachineId = sprintf('%s.controller_state_machine.%s', $applicationName, $resourceName);

            $stateMachineComponent = $configuration['state_machine_component'] ?? null;

            if (null === $stateMachineComponent) {
                $container->setAlias($stateMachineId, 'sylius.resource_controller.state_machine');

                continue;
            }

            $specificStateMachineId = sprintf('sylius.resource_controller.state_machine.%s', $stateMachineComponent);

            if (!$container->hasDefinition($specificStateMachineId)) {
                throw new \LogicException(sprintf('State machine "%s" is not available.', $stateMachineComponent));
            }

            $container->setAlias($stateMachineId, $specificStateMachineId);
        }
    }
}

\class_alias(RegisterResourceStateMachinePass::class, \Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterResourceStateMachinePass::class);
