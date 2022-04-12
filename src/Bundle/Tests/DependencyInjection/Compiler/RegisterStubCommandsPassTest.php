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
use Sylius\Bundle\ResourceBundle\Command\StubMakeResourceTransformer;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterStubCommandsPass;
use Sylius\Bundle\ResourceBundle\Maker\MakeResourceTransformer;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterStubCommandsPassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_registers_stub_commands_when_marker_is_not_registered(): void
    {
        $this->compile();

        $this->assertContainerBuilderHasService(StubMakeResourceTransformer::class, StubMakeResourceTransformer::class);
    }

    /** @test */
    public function it_does_not_register_stub_commands_when_marker_is_registered(): void
    {
        $this->setParameter('kernel.bundles', [MakerBundle::class]);

        $this->compile();

        $this->assertContainerBuilderNotHasService(StubMakeResourceTransformer::class);
    }

    /** @test */
    public function it_unregisters_definition_for_resource_transformer_maker_when_maker_is_not_registered(): void
    {
        $this->registerService('sylius.resource_transformer.maker', MakeResourceTransformer::class);

        $this->compile();

        $this->assertContainerBuilderNotHasService('sylius.resource_transformer.maker');
    }

    /** @test */
    public function it_does_not_unregister_definition_for_resource_transformer_maker_when_maker_is_registered(): void
    {
        $this->setParameter('kernel.bundles', [MakerBundle::class]);
        $this->registerService('sylius.resource_transformer.maker', MakeResourceTransformer::class);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_transformer.maker', MakeResourceTransformer::class);
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $this->setParameter('kernel.bundles', []);

        $container->addCompilerPass(new RegisterStubCommandsPass());
    }
}
