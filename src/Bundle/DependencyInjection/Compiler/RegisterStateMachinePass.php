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

use Sylius\Bundle\ResourceBundle\Controller\StateMachine;
use Sylius\Bundle\ResourceBundle\Controller\Workflow;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

final class RegisterStateMachinePass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container): void
    {
        /** @var array $settings */
        $settings = $container->getParameter('sylius.resource.settings');
        $stateMachine = $settings['state_machine'];

        if (
            null === $stateMachine
            && !$this->isSymfonyWorkflowEnabled($container)
            && !$this->isWinzouStateMachineEnabled($container)
        ) {
            return;
        }

        $stateMachine = $stateMachine ?? ResourceBundleInterface::STATE_MACHINE_SYMFONY;

        if (ResourceBundleInterface::STATE_MACHINE_SYMFONY === $stateMachine) {
            $this->setSymfonyWorkflowAsStateMachine($container);

            return;
        }

        if (ResourceBundleInterface::STATE_MACHINE_WINZOU === $stateMachine) {
            $this->setWinzouAsStateMachine($container);

            return;
        }
    }

    private function setWinzouAsStateMachine(ContainerBuilder $container): void
    {
        if (!$this->isWinzouStateMachineEnabled($container)) {
            throw new \LogicException('You can not use "Winzou" for your state machine if it is not available. Try running "composer require winzou/state-machine-bundle".');
        }

        $stateMachineDefinition = $container->register('sylius.resource_controller.state_machine', StateMachine::class);
        $stateMachineDefinition->setPublic(false);
        $stateMachineDefinition->addArgument(new Reference('sm.factory'));
    }

    private function setSymfonyWorkflowAsStateMachine(ContainerBuilder $container): void
    {
        if (!$this->isSymfonyWorkflowEnabled($container)) {
            if (class_exists(Workflow::class)) {
                throw new \LogicException('You can not use "Symfony" for your state machine if it is not enabled on framework bundle.');
            }

            throw new \LogicException('You can not use "Symfony" for your state machine if it is not available. Try running "composer require symfony/workflow".');
        }

        $stateMachineDefinition = $container->register('sylius.resource_controller.state_machine', Workflow::class);
        $stateMachineDefinition->setPublic(false);
        $stateMachineDefinition->addArgument(new Reference('workflow.registry'));
    }

    private function isSymfonyWorkflowEnabled(ContainerBuilder $container): bool
    {
        return $container->hasDefinition('workflow.registry');
    }

    private function isWinzouStateMachineEnabled(ContainerBuilder $container): bool
    {
        $bundles = $container->getParameter('kernel.bundles');

        return in_array(winzouStateMachineBundle::class, $bundles, true);
    }
}
