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

namespace spec\Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolverInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\AnimalInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\Bear;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\BearInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\Fly;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\FlyInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\MammalInterface;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\Resource;

class TargetEntitiesResolverSpec extends ObjectBehavior
{
    function it_is_a_target_entities_resolver(): void
    {
        $this->shouldImplement(TargetEntitiesResolverInterface::class);
    }

    function it_skips_resource_interface(): void
    {
        $emptyConfig = ['app.resource' => ['classes' => ['model' => Resource::class]]];

        $this->resolve($emptyConfig)->shouldReturn([]);
    }

    function it_autodiscovers_interfaces_based_on_the_model_class(): void
    {
        $flyConfig = ['app.fly' => ['classes' => ['model' => Fly::class]]];

        $this->resolve($flyConfig)->shouldHaveCount(2);
        $this->resolve($flyConfig)->shouldHaveKeyWithValue(FlyInterface::class, Fly::class);
        $this->resolve($flyConfig)->shouldHaveKeyWithValue(AnimalInterface::class, Fly::class);

        $bearConfig = ['app.bear' => ['classes' => ['model' => Bear::class]]];

        $this->resolve($bearConfig)->shouldHaveCount(3);
        $this->resolve($bearConfig)->shouldHaveKeyWithValue(BearInterface::class, Bear::class);
        $this->resolve($bearConfig)->shouldHaveKeyWithValue(MammalInterface::class, Bear::class);
        $this->resolve($bearConfig)->shouldHaveKeyWithValue(AnimalInterface::class, Bear::class);
    }

    function it_autodiscovers_only_unique_interfaces_based_on_model_classes(): void
    {
        $config = [
            'app.fly' => ['classes' => ['model' => Fly::class]],
            'app.bear' => ['classes' => ['model' => Bear::class]],
        ];

        $this->resolve($config)->shouldHaveCount(3);
        $this->resolve($config)->shouldHaveKeyWithValue(BearInterface::class, Bear::class);
        $this->resolve($config)->shouldHaveKeyWithValue(MammalInterface::class, Bear::class);
        $this->resolve($config)->shouldHaveKeyWithValue(FlyInterface::class, Fly::class);

        $this->resolve($config)->shouldNotHaveKeyWithValue(AnimalInterface::class, Fly::class);
        $this->resolve($config)->shouldNotHaveKeyWithValue(AnimalInterface::class, Bear::class);
    }

    function it_uses_the_interface_defined_in_the_config(): void
    {
        $config = [
            'app.deprecated' => ['classes' => ['model' => Resource::class, 'interface' => \Countable::class]],
        ];

        $this->resolve($config)->shouldHaveCount(1);
        $this->resolve($config)->shouldHaveKeyWithValue(\Countable::class, Resource::class);
        $this->shouldTrigger(\E_USER_DEPRECATED)->during('resolve', [$config]);
    }

    function it_uses_the_interface_defined_explicitly_over_the_autodiscovered_one(): void
    {
        $config = [
            'app.deprecated' => ['classes' => ['model' => Resource::class, 'interface' => MammalInterface::class]],
            'app.bear' => ['classes' => ['model' => Bear::class]],
        ];

        $this->resolve($config)->shouldHaveCount(3);
        $this->resolve($config)->shouldHaveKeyWithValue(MammalInterface::class, Resource::class);
        $this->resolve($config)->shouldHaveKeyWithValue(AnimalInterface::class, Bear::class);
        $this->resolve($config)->shouldHaveKeyWithValue(BearInterface::class, Bear::class);
        $this->shouldTrigger(\E_USER_DEPRECATED)->during('resolve', [$config]);
    }

    function it_throws_an_exception_if_model_class_can_not_be_resolved(): void
    {
        $config = ['app.error' => ['classes' => ['interface' => \Countable::class]]];

        $this->shouldThrow(\InvalidArgumentException::class)->during('resolve', [$config]);
    }
}
