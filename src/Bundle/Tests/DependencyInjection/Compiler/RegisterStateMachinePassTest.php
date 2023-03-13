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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use SM\Factory\Factory;
use Sylius\Bundle\ResourceBundle\Controller\StateMachine;
use Sylius\Bundle\ResourceBundle\Controller\Workflow;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterStateMachinePass;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Workflow\Workflow as SymfonyWorkflow;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

final class RegisterStateMachinePassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_does_nothing_when_no_state_machine_are_available(): void
    {
        $this->setParameter('sylius.resource.settings', ['state_machine_component' => null]);

        $this->compile();

        $this->assertContainerBuilderHasParameter('sylius.state_machine_component.default', null);
        $this->assertContainerBuilderNotHasService('sylius.resource_controller.state_machine');
        $this->assertContainerBuilderNotHasService('sylius.resource_controller.state_machine.symfony');
        $this->assertContainerBuilderNotHasService('sylius.resource_controller.state_machine.winzou');
    }

    /** @test */
    public function it_registers_state_machine_controller_with_symfony_workflow_when_its_configured_to(): void
    {
        $this->setParameter('sylius.resource.settings', ['state_machine_component' => ResourceBundleInterface::STATE_MACHINE_SYMFONY]);
        $this->makeSymfonyWorkflowAvailable();

        $this->compile();

        $this->assertContainerBuilderHasParameter('sylius.state_machine_component.default', 'symfony');
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine', Workflow::class);
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine.symfony', Workflow::class);
        $this->assertContainerBuilderNotHasService('sylius.resource_controller.state_machine.winzou');
    }

    /** @test */
    public function it_registers_state_machine_controller_with_winzou_when_its_configured_to(): void
    {
        $this->setParameter('sylius.resource.settings', ['state_machine_component' => ResourceBundleInterface::STATE_MACHINE_WINZOU]);
        $this->makeWinzouStateMachineAvailable();

        $this->compile();

        $this->assertContainerBuilderHasParameter('sylius.state_machine_component.default', 'winzou');
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine', StateMachine::class);
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine.winzou', StateMachine::class);
        $this->assertContainerBuilderNotHasService('sylius.resource_controller.state_machine.symfony');
    }

    /** @test */
    public function it_registers_state_machine_controller_with_symfony_workflow_by_default_when_only_this_one_is_available(): void
    {
        $this->setParameter('sylius.resource.settings', ['state_machine_component' => null]);
        $this->makeSymfonyWorkflowAvailable();

        $this->compile();

        $this->assertContainerBuilderHasParameter('sylius.state_machine_component.default', 'symfony');
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine', Workflow::class);
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine.symfony', Workflow::class);
        $this->assertContainerBuilderNotHasService('sylius.resource_controller.state_machine.winzou');
    }

    /** @test */
    public function it_registers_state_machine_controller_with_winzou_by_default_when_only_this_one_is_available(): void
    {
        $this->setParameter('sylius.resource.settings', ['state_machine_component' => null]);
        $this->makeWinzouStateMachineAvailable();

        $this->compile();

        $this->assertContainerBuilderHasParameter('sylius.state_machine_component.default', 'winzou');
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine', StateMachine::class);
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine.winzou', StateMachine::class);
        $this->assertContainerBuilderNotHasService('sylius.resource_controller.state_machine.symfony');
    }

    /** @test */
    public function it_registers_state_machine_controller_with_winzou_by_default_when_both_are_available(): void
    {
        $this->setParameter('sylius.resource.settings', ['state_machine_component' => null]);
        $this->makeWinzouStateMachineAvailable();
        $this->makeSymfonyWorkflowAvailable();

        $this->compile();

        $this->assertContainerBuilderHasParameter('sylius.state_machine_component.default', 'winzou');
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine', StateMachine::class);
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine.winzou', StateMachine::class);
        $this->assertContainerBuilderHasService('sylius.resource_controller.state_machine.symfony', Workflow::class);
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->setParameter('sylius.resource.settings', ['state_machine_component' => null]);

        $container->addCompilerPass(new RegisterStateMachinePass());
    }

    private function makeWinzouStateMachineAvailable(): void
    {
        $this->setParameter('kernel.bundles', ['Winzou/StateMachine' => winzouStateMachineBundle::class]);
        $this->registerService('sm.factory', Factory::class);
    }

    private function makeSymfonyWorkflowAvailable(): void
    {
        $this->registerService('workflow.registry', SymfonyWorkflow::class);
    }
}
