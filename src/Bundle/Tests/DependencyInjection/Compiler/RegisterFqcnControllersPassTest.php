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
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterFqcnControllersPass;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

final class RegisterFqcnControllersPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_registers_alias_for_resource_controller_as_a_FQCN(): void
    {
        $this->setDefinition('sylius.resource_registry', new Definition());
        $this->setDefinition('app.controller.book', new Definition());

        $this->setParameter(
            'sylius.resources',
            [
                'app.book' => [
                    'driver' => 'doctrine/orm',
                    'classes' => ['model' => BookTestClass::class, 'controller' => BookTestController::class],
                ],
            ]
        );

        $this->compile();

        $this->assertContainerBuilderHasService(BookTestController::class);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_class_does_not_implement_resource_interface(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->setParameter(
            'sylius.resources',
            [
                'app.normalClass' => [
                    'driver' => 'doctrine/orm',
                    'classes' => ['model' => NormalClass::class],
                ],
            ]
        );

        $this->compile();
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterFqcnControllersPass());
    }
}

class BookTestClass implements ResourceInterface
{
    public function getId()
    {
    }
}

class NormalClass
{
}

class BookTestController extends ResourceController
{
}
