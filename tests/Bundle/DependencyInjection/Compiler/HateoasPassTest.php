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

namespace Bundle\DependencyInjection\Compiler;

use Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\HateoasPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class HateoasPassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_remove_pagerfanta_representation_factory_if_hateoas_is_not_available(): void
    {
        $this->setParameter('kernel.bundles', []);

        $this->compile();

        $this->assertContainerBuilderNotHasService('sylius.resource_controller.pagerfanta_representation_factory');
    }

    /** @test */
    public function it_keeps_the_view_handler_if_fos_rest_is_available(): void
    {
        $this->setParameter('kernel.bundles', [BazingaHateoasBundle::class]);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_controller.pagerfanta_representation_factory');
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $this->registerService('sylius.resource_controller.pagerfanta_representation_factory', PagerfantaFactory::class);
        $this->setParameter('kernel.bundles', []);

        $container->addCompilerPass(new HateoasPass());
    }
}
