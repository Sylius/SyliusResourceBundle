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

namespace spec\Sylius\Resource\Twig\Context\Factory;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Twig\Context\Factory\ContextFactory;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;

final class ContextFactorySpec extends ObjectBehavior
{
    function let(ContainerInterface $locator): void
    {
        $this->beConstructedWith($locator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ContextFactory::class);
    }

    function it_creates_twig_context_from_operation_twig_context_factory_as_callable(
        \stdClass $data,
        ContainerInterface $locator,
    ): void {
        $twigContextFactory = [TwigContextFactoryCallable::class, 'create'];

        $operation = new Show(twigContextFactory: $twigContextFactory);

        $context = new Context();

        $this->create($data, $operation, $context)->shouldReturn([
            'foo' => 'bar',
        ]);
    }

    function it_creates_twig_context_from_operation_twig_context_factory_as_string(
        \stdClass $data,
        ContainerInterface $locator,
        ContextFactoryInterface $twigContextFactory,
    ): void {
        $operation = new Show(twigContextFactory: $twigContextFactory::class);

        $context = new Context();

        $locator->has($twigContextFactory::class)->willReturn(true)->shouldBeCalled();
        $locator->get($twigContextFactory::class)->willReturn($twigContextFactory)->shouldBeCalled();

        $twigContextFactory->create($data, $operation, $context)->willReturn([
            'foo' => 'bar',
        ]);

        $this->create($data, $operation, $context)->shouldReturn([
            'foo' => 'bar',
        ]);
    }

    function it_throws_an_exception_when_twig_context_factory_was_not_found_on_the_locator(
        \stdClass $data,
        ContainerInterface $locator,
        ContextFactoryInterface $twigContextFactory,
    ): void {
        $operation = new Show(name: 'app_dummy_show', twigContextFactory: $twigContextFactory::class);

        $context = new Context();

        $locator->has($twigContextFactory::class)->willReturn(false)->shouldBeCalled();

        $this->shouldThrow(new \RuntimeException(sprintf('Twig context factory "%s" not found on operation "app_dummy_show"', $twigContextFactory::class)))
            ->during('create', [$data, $operation, $context])
        ;
    }
}

final class TwigContextFactoryCallable
{
    public static function create(mixed $data, Operation $operation, Context $context): array
    {
        return [
            'foo' => 'bar',
        ];
    }
}
