<?php

/*
 * This file is part of sylius-resource-bundle.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use SM\Factory\Factory;
use Sylius\Bundle\ResourceBundle\Controller\StateMachine;
use Sylius\Bundle\ResourceBundle\Controller\Workflow;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterStateMachinePass;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

final class RegisterStateMachinePassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_registers_state_machine_controller_with_symfony_workflow_when_available(): void
    {
        $this->setParameter('sylius.resource.settings', ['state_machine' => null]);
        $this->registerService('workflow.registry', Workflow::class);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine', Workflow::class);
    }

    /**
     * @test
     */
    public function it_registers_state_machine_controller_with_winzou_when_available(): void
    {
        $this->setParameter('kernel.bundles', ['Winzou/StateMachine' => winzouStateMachineBundle::class]);
        $this->setParameter('sylius.resource.settings', ['state_machine' => ResourceBundleInterface::STATE_MACHINE_WINZOU]);
        $this->registerService('sm.factory', Factory::class);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine', StateMachine::class);
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterStateMachinePass());
    }
}
