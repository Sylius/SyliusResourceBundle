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

namespace spec\Sylius\Resource\State;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Tests\Dummy\ProviderWithCallable;
use Sylius\Resource\Context\Context;
use Sylius\Resource\State\Provider;
use Sylius\Resource\State\ProviderInterface;

final class ProviderSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator): void
    {
        $this->beConstructedWith($locator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Provider::class);
    }

    function it_calls_provide_method_from_operation_provider_as_string(
        ContainerInterface $locator,
        ProviderInterface $provider,
    ): void {
        $operation = new Create(provider: '\App\Provider');
        $context = new Context();

        $locator->has('\App\Provider')->willReturn(true);
        $locator->get('\App\Provider')->willReturn($provider);

        $provider->provide($operation, $context)->shouldBeCalled();

        $this->provide($operation, $context);
    }

    function it_calls_provide_method_from_operation_provider_as_callable(): void
    {
        $operation = new Create(provider: [ProviderWithCallable::class, 'provide']);
        $context = new Context();

        $this->provide($operation, $context)->shouldHaveType(\stdClass::class);
    }

    function it_returns_null_if_operation_has_no_provider(): void
    {
        $operation = new Create();
        $context = new Context();

        $this->provide($operation, $context)->shouldReturn(null);
    }

    function it_throws_an_exception_when_configured_provider_is_not_a_provider_instance(
        ContainerInterface $locator,
    ): void {
        $operation = new Create(provider: '\stdClass');
        $context = new Context();

        $locator->has('\stdClass')->willReturn(true);
        $locator->get('\stdClass')->willReturn(new \stdClass());

        $this->shouldThrow(new \InvalidArgumentException('Expected an instance of Sylius\Resource\State\ProviderInterface. Got: stdClass'))
            ->during('provide', [$operation, $context])
        ;
    }
}
