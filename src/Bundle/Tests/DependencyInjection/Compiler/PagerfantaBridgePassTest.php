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

use BabDev\PagerfantaBundle\Twig\PagerfantaExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Pagerfanta\View\ViewFactory;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PagerfantaBridgePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PagerfantaBridgePassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_creates_aliased_services_and_changes_the_view_factory_class(): void
    {
        $this->registerService('pagerfanta.twig_extension', PagerfantaExtension::class);
        $this->registerService('pagerfanta.view_factory', ViewFactory::class);

        $this->setParameter('white_october_pagerfanta.view_factory.class', 'My\ViewFactory');

        $this->compile();

        $this->assertContainerBuilderHasAlias('twig.extension.pagerfanta', 'pagerfanta.twig_extension');
        $this->assertContainerBuilderHasAlias('white_october_pagerfanta.view_factory', 'pagerfanta.view_factory');
        $this->assertContainerBuilderHasService('pagerfanta.view_factory', 'My\ViewFactory');
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new PagerfantaBridgePass());
    }
}
