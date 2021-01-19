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
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterControllerAliasesPass;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

final class RegisterControllerAliasesPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_throws_exception_if_resource_does_not_exist(): void
    {
        $this->compile();

        $this->throwException(new InvalidArgumentException());
    }

    /**
     * @test
     */
    public function it_register_alias_for_resource_controller_as_a_FQCN(): void
    {
        $this->setDefinition('sylius.resource_registry', new Definition());
        $this->setDefinition('app.controller.book', new Definition());

        $this->setParameter(
            'sylius.resources',
            [
                'app.book' => [
                    'driver' => 'doctrine/orm',
                    'classes' => ['model' => BookTestClass::class, 'controller' => BookTestController::class]
                ],
            ]
        );

        $this->compile();

        $this->assertContainerBuilderHasService(BookTestController::class);
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterControllerAliasesPass());
    }

}

class BookTestClass implements ResourceInterface
{
    public function getId()
    {
    }
}
class BookTestController extends ResourceController
{
}
