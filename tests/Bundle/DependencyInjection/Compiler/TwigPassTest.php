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

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\TwigPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Environment;

final class TwigPassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_makes_twig_public(): void
    {
        if (!class_exists(Environment::class)) {
            self::markTestSkipped('Twig is not installed');
        }

        $this->registerService('twig', Environment::class);

        $this->compile();

        $tokenManagerDefinition = $this->container->getDefinition('twig');
        $this->assertTrue($tokenManagerDefinition->isPublic());
    }

    /** @test */
    public function it_does_nothing_if_twig_is_not_registered(): void
    {
        if (!class_exists(Environment::class)) {
            self::markTestSkipped('Twig is not installed');
        }

        $this->compile();

        $this->assertFalse($this->container->hasDefinition('twig'));
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigPass());
    }
}
