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

namespace DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\Bundle\ResourceBundle\Controller\StateMachine;
use Sylius\Bundle\ResourceBundle\Controller\Workflow;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterResourceStateMachinePass;
use Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler\AuthorClass;
use Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler\BookClass;
use Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler\PullRequestClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class RegisterResourceStateMachinePassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_registers_state_machine_components_for_resources(): void
    {
        $this->registerService('sylius.resource_controller.state_machine', StateMachine::class);
        $this->makeSymfonyWorkflowAvailable();
        $this->makeWinzouStateMachineAvailable();

        $this->setDefinition('sylius.resource_registry', new Definition());

        $this->setParameter(
            'sylius.resources',
            [
                'app.book' => ['state_machine_component' => 'symfony', 'classes' => ['model' => BookClass::class]],
                'app.author' => ['state_machine_component' => 'winzou', 'classes' => ['model' => AuthorClass::class]],
                'app.pull_request' => ['classes' => ['model' => PullRequestClass::class]],
            ],
        );

        $this->compile();

        $this->assertContainerBuilderHasAlias('app.controller_state_machine.book', 'sylius.resource_controller.state_machine.symfony');
        $this->assertContainerBuilderHasAlias('app.controller_state_machine.author', 'sylius.resource_controller.state_machine.winzou');
        $this->assertContainerBuilderHasAlias('app.controller_state_machine.pull_request', 'sylius.resource_controller.state_machine');
    }

    /** @test */
    public function it_throws_an_exception_when_specific_symfony_state_machine_component_is_not_available(): void
    {
        $this->registerService('sylius.resource_controller.state_machine', StateMachine::class);
        $this->makeWinzouStateMachineAvailable();

        $this->setDefinition('sylius.resource_registry', new Definition());

        $this->setParameter(
            'sylius.resources',
            [
                'app.book' => ['state_machine_component' => 'symfony', 'classes' => ['model' => BookClass::class]],
                'app.author' => ['state_machine_component' => 'winzou', 'classes' => ['model' => AuthorClass::class]],
                'app.pull_request' => ['classes' => ['model' => PullRequestClass::class]],
            ],
        );

        $error = false;

        try {
            $this->compile();
        } catch (\LogicException $exception) {
            $error = true;
            $message = $exception->getMessage();
        }

        $this->assertTrue($error, 'Should not compile');
        $this->assertEquals('State machine "symfony" is not available.', $message);
    }

    /** @test */
    public function it_throws_an_exception_when_specific_winzou_state_machine_component_is_not_available(): void
    {
        $this->registerService('sylius.resource_controller.state_machine', Workflow::class);
        $this->makeSymfonyWorkflowAvailable();

        $this->setDefinition('sylius.resource_registry', new Definition());

        $this->setParameter(
            'sylius.resources',
            [
                'app.book' => ['state_machine_component' => 'symfony', 'classes' => ['model' => BookClass::class]],
                'app.author' => ['state_machine_component' => 'winzou', 'classes' => ['model' => AuthorClass::class]],
                'app.pull_request' => ['classes' => ['model' => PullRequestClass::class]],
            ],
        );

        $error = false;

        try {
            $this->compile();
        } catch (\LogicException $exception) {
            $error = true;
            $message = $exception->getMessage();
        }

        $this->assertTrue($error, 'Should not compile');
        $this->assertEquals('State machine "winzou" is not available.', $message);
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterResourceStateMachinePass());
    }

    private function makeWinzouStateMachineAvailable(): void
    {
        $this->registerService('sylius.resource_controller.state_machine.winzou', StateMachine::class);
    }

    private function makeSymfonyWorkflowAvailable(): void
    {
        $this->registerService('sylius.resource_controller.state_machine.symfony', Workflow::class);
    }
}
