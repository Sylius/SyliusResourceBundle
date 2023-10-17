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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\CsrfTokenManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CrsfTokenManagerPassTest extends AbstractCompilerPassTestCase
{
    /** @test */
    public function it_makes_csrf_token_manager_public(): void
    {
        if (!interface_exists(CsrfTokenManagerInterface::class)) {
            self::markTestSkipped('Security is not installed');
        }

        $this->registerService('security.csrf.token_manager', CsrfTokenManagerInterface::class);

        $this->compile();

        $tokenManagerDefinition = $this->container->getDefinition('security.csrf.token_manager');
        $this->assertTrue($tokenManagerDefinition->isPublic());
    }

    /** @test */
    public function it_does_nothing_if_csrf_token_manager_is_not_registered(): void
    {
        if (!interface_exists(CsrfTokenManagerInterface::class)) {
            self::markTestSkipped('Security is not installed');
        }

        $this->compile();

        $this->assertFalse($this->container->hasDefinition('security.csrf.token_manager'));
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CsrfTokenManagerPass());
    }
}
