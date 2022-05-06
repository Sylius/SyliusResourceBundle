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
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterFormBuilderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterFormBuilderPassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_registers_default_form_builder(): void
    {
        $this->setDefinition('sylius.registry.form_builder', new Definition());

        $this->setDefinition(
            'default_form_builder',
            (new Definition())
                ->addTag('sylius.default_resource_form.builder', ['type' => 'foo'])
                ->addTag('sylius.default_resource_form.builder', ['type' => 'bar'])
        );

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.registry.form_builder',
            'register',
            ['foo', new Reference('default_form_builder')]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.registry.form_builder',
            'register',
            ['bar', new Reference('default_form_builder')]
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterFormBuilderPass());
    }
}
