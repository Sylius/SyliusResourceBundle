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
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\DoctrineTargetEntitiesResolverPass;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolverInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class DoctrineTargetEntitiesResolverPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_adds_method_call_to_resolve_doctrine_target_entities(): void
    {
        $this->setDefinition('doctrine.orm.listeners.resolve_target_entity', new Definition());

        $this->setParameter(
            'sylius.resources',
            ['app.loremipsum' => ['classes' => ['model' => \stdClass::class, 'interface' => \Countable::class]]]
        );

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'doctrine.orm.listeners.resolve_target_entity',
            'addResolveTargetEntity',
            [\Countable::class, \stdClass::class, []]
        );
    }

    /**
     * @test
     */
    public function it_adds_doctrine_event_listener_tag_to_target_entities_resolver_if_not_exists(): void
    {
        $this->setDefinition('doctrine.orm.listeners.resolve_target_entity', new Definition());
        $this->setParameter('sylius.resources', []);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'doctrine.orm.listeners.resolve_target_entity',
            'doctrine.event_listener',
            ['event' => 'loadClassMetadata']
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $targetEntitiesResolver = new class() implements TargetEntitiesResolverInterface {
            public function resolve(array $resourcesConfiguration): array
            {
                if ($resourcesConfiguration === ['app.loremipsum' => ['classes' => ['model' => \stdClass::class, 'interface' => \Countable::class]]]) {
                    return [\Countable::class => \stdClass::class];
                }

                return [];
            }
        };

        $container->addCompilerPass(new DoctrineTargetEntitiesResolverPass($targetEntitiesResolver));
    }
}
