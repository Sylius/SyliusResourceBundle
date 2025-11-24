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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler\Helper;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolverInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\AnimalInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\Bear;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\BearInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\Fly;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\FlyInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\MammalInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\Resource;

final class TargetEntitiesResolverTest extends TestCase
{
    private TargetEntitiesResolverInterface $resolver;

    protected function setUp(): void
    {
        $this->resolver = new TargetEntitiesResolver();
    }

    public function testItIsATargetEntitiesResolver(): void
    {
        $this->assertInstanceOf(TargetEntitiesResolverInterface::class, $this->resolver);
    }

    public function testItSkipsResourceInterface(): void
    {
        $emptyConfig = ['app.resource' => ['classes' => ['model' => Resource::class]]];

        $this->assertSame([], $this->resolver->resolve($emptyConfig));
    }

    public function testItAutodiscoversInterfacesBasedOnTheModelClass(): void
    {
        $flyConfig = ['app.fly' => ['classes' => ['model' => Fly::class]]];

        $resolved = $this->resolver->resolve($flyConfig);

        $this->assertCount(2, $resolved);
        $this->assertSame(Fly::class, $resolved[FlyInterface::class]);
        $this->assertSame(Fly::class, $resolved[AnimalInterface::class]);

        $bearConfig = ['app.bear' => ['classes' => ['model' => Bear::class]]];

        $resolved = $this->resolver->resolve($bearConfig);

        $this->assertCount(3, $resolved);
        $this->assertSame(Bear::class, $resolved[BearInterface::class]);
        $this->assertSame(Bear::class, $resolved[MammalInterface::class]);
        $this->assertSame(Bear::class, $resolved[AnimalInterface::class]);
    }

    public function testItAutodiscoversOnlyUniqueInterfacesBasedOnModelClasses(): void
    {
        $config = [
            'app.fly' => ['classes' => ['model' => Fly::class]],
            'app.bear' => ['classes' => ['model' => Bear::class]],
        ];

        $resolved = $this->resolver->resolve($config);

        $this->assertCount(3, $resolved);
        $this->assertSame(Bear::class, $resolved[BearInterface::class]);
        $this->assertSame(Bear::class, $resolved[MammalInterface::class]);
        $this->assertSame(Fly::class, $resolved[FlyInterface::class]);

        $this->assertArrayNotHasKey(AnimalInterface::class, $resolved);
    }

    public function testItAutodiscoversInterfacesOnModelsWhenPassedMultipleTimes(): void
    {
        $config = [
            'app.fly' => ['classes' => ['model' => Fly::class]],
            'app.another_resource_with_fly_model' => ['classes' => ['model' => Fly::class]],
        ];

        $resolved = $this->resolver->resolve($config);

        $this->assertCount(2, $resolved);
        $this->assertSame(Fly::class, $resolved[FlyInterface::class]);
        $this->assertSame(Fly::class, $resolved[AnimalInterface::class]);
    }

    public function testItUsesTheInterfaceDefinedInTheConfig(): void
    {
        $config = [
            'app.deprecated' => ['classes' => ['model' => Resource::class, 'interface' => \Countable::class]],
        ];

        $resolved = @$this->resolver->resolve($config);

        $this->assertCount(1, $resolved);
        $this->assertSame(Resource::class, $resolved[\Countable::class]);
    }

    public function testItUsesTheInterfaceDefinedExplicitlyOverTheAutodiscoveredOne(): void
    {
        $config = [
            'app.deprecated' => ['classes' => ['model' => Resource::class, 'interface' => MammalInterface::class]],
            'app.bear' => ['classes' => ['model' => Bear::class]],
        ];

        $resolved = @$this->resolver->resolve($config);

        $this->assertCount(3, $resolved);
        $this->assertSame(Resource::class, $resolved[MammalInterface::class]);
        $this->assertSame(Bear::class, $resolved[AnimalInterface::class]);
        $this->assertSame(Bear::class, $resolved[BearInterface::class]);
    }

    public function testItThrowsAnExceptionIfModelClassCannotBeResolved(): void
    {
        $config = ['app.error' => ['classes' => ['interface' => \Countable::class]]];

        $this->expectException(\InvalidArgumentException::class);

        $this->resolver->resolve($config);
    }
}
