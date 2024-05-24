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

namespace Sylius\Component\Resource\tests\Symfony\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\Resource\Symfony\DependencyInjection\Compiler\DisableMetadataCachePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class DisableMetadataCachePassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_disables_cache_when_debug_is_enabled(): void
    {
        $this->setDefinition('sylius.resource_metadata_collection.factory.cached', new Definition());
        $this->setParameter('kernel.debug', true);

        $this->compile();

        $this->assertContainerBuilderNotHasService('sylius.resource_metadata_collection.factory.cached');
    }

    /** @test */
    public function it_does_not_disable_cache_when_debug_is_disabled(): void
    {
        $this->setDefinition('sylius.resource_metadata_collection.factory.cached', new Definition());
        $this->setParameter('kernel.debug', false);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_metadata_collection.factory.cached');
    }

    /** @test */
    public function it_does_not_disable_cache_when_debug_parameter_does_not_exist(): void
    {
        $this->setDefinition('sylius.resource_metadata_collection.factory.cached', new Definition());

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_metadata_collection.factory.cached');
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DisableMetadataCachePass());
    }
}
