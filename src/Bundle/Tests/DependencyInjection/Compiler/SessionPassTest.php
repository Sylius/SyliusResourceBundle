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
use Sylius\Bundle\ResourceBundle\Controller\FlashHelper;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\SessionPass;
use Sylius\Bundle\ResourceBundle\Storage\SessionStorage;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestStack;

final class SessionPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_has_the_correct_argument(): void
    {
        if (method_exists(RequestStack::class, 'getSession')) {
            self::markTestSkipped('RequestStack::getSession() is available');
        }

        $this->setDefinition('sylius.resource_controller.flash_helper', new Definition(FlashHelper::class, [new Reference('request_stack'), new Reference('translator'), 'en']));
        $this->setDefinition('sylius.storage.session', new Definition(SessionStorage::class, [new Reference('request_stack')]));

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('sylius.resource_controller.flash_helper', 0, new Reference('session'));
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('sylius.storage.session', 0, new Reference('session'));
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new SessionPass());
    }
}
