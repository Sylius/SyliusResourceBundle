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

namespace Sylius\Resource\Tests\Symfony\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use PHPUnit\Framework\Attributes\Test;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelper;
use Sylius\Resource\Symfony\DependencyInjection\Compiler\FallbackToKernelDefaultLocalePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class FallbackToKernelDefaultLocalePassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_does_nothing_if_locale_parameter_has_been_defined(): void
    {
        $this->setParameter('locale', 'en_US');
        $this->setDefinition('sylius.resource_controller.flash_helper', new Definition());

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_controller.flash_helper');
    }

    /** @test */
    public function it_replaces_locale_argument_in_flash_helper(): void
    {
        $flashHelperDefinition = (new Definition(FlashHelper::class))->setArguments([
            null,
            null,
            null,
        ]);

        $this->setParameter('kernel.default_locale', 'en_US');
        $this->setDefinition('sylius.resource_controller.flash_helper', $flashHelperDefinition);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_controller.flash_helper');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('sylius.resource_controller.flash_helper', 2, 'en_US');
    }

    /** @test */
    public function it_replaces_locale_argument_in_translation_locale_provider(): void
    {
        $translationLocaleProviderDefinition = (new Definition(FlashHelper::class))->setArguments([
            [],
            null,
        ]);

        $this->setParameter('kernel.default_locale', 'en_US');
        $this->setDefinition('sylius.translation_locale_provider.immutable', $translationLocaleProviderDefinition);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.translation_locale_provider.immutable');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('sylius.translation_locale_provider.immutable', 0, ['en_US']);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('sylius.translation_locale_provider.immutable', 1, 'en_US');
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new FallbackToKernelDefaultLocalePass());
    }
}
