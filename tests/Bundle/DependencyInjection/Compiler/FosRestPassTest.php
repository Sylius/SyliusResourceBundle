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

use FOS\RestBundle\FOSRestBundle;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandler;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\FosRestPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class FosRestPassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_remove_view_handler_if_fos_rest_is_not_available(): void
    {
        $this->setParameter('kernel.bundles', []);

        $this->compile();

        $this->assertContainerBuilderNotHasService('sylius.resource_controller.view_handler');
    }

    /** @test */
    public function it_keeps_the_view_handler_if_fos_rest_is_available(): void
    {
        $this->setParameter('kernel.bundles', [FOSRestBundle::class]);

        $this->compile();

        $this->assertContainerBuilderHasService('sylius.resource_controller.view_handler');
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $this->registerService('sylius.resource_controller.view_handler', ViewHandler::class);
        $this->setParameter('kernel.bundles', []);

        $container->addCompilerPass(new FosRestPass());
    }
}
