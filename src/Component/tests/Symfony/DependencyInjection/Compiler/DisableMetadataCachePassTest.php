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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sylius\Resource\Symfony\DependencyInjection\Compiler\DisableMetadataCachePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

#[CoversClass(DisableMetadataCachePass::class)]
final class DisableMetadataCachePassTest extends AbstractCompilerPassTestCase
{
    #[Test]
    #[DataProvider('getCachedServiceIdProvider')]
    public function it_disables_cache_when_debug_is_enabled(string $cachedServiceId): void
    {
        $this->setDefinition($cachedServiceId, new Definition());
        $this->setParameter('kernel.debug', true);

        $this->compile();

        $this->assertContainerBuilderNotHasService($cachedServiceId);
    }

    #[Test]
    #[DataProvider('getCachedServiceIdProvider')]
    public function it_does_not_disable_cache_when_debug_is_disabled(string $cachedServiceId): void
    {
        $this->setDefinition($cachedServiceId, new Definition());
        $this->setParameter('kernel.debug', false);

        $this->compile();

        $this->assertContainerBuilderHasService($cachedServiceId);
    }

    #[Test]
    #[DataProvider('getCachedServiceIdProvider')]
    public function it_does_not_disable_cache_when_debug_parameter_does_not_exist(string $cachedServiceId): void
    {
        $this->setDefinition('sylius.resource_metadata_collection.factory.cached', new Definition());

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_metadata_collection.factory.cached');
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DisableMetadataCachePass());
    }

    public static function getCachedServiceIdProvider(): iterable
    {
        yield ['sylius.resource_metadata_collection.factory.cached'];
        yield ['sylius.metadata.resource_class_list.cached'];
    }
}
