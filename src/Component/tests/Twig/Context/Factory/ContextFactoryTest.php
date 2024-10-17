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

namespace Sylius\Resource\Tests\Twig\Context\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Twig\Context\Factory\ContextFactory;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;

final class ContextFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ContextFactory $contextFactory;

    private ContainerInterface|ObjectProphecy $locator;

    protected function setUp(): void
    {
        $this->locator = $this->prophesize(ContainerInterface::class);
        $this->contextFactory = new ContextFactory($this->locator->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ContextFactory::class, $this->contextFactory);
    }

    public function testItCreatesTwigContextFromOperationTwigContextFactoryAsCallable(): void
    {
        $data = new \stdClass();
        $twigContextFactory = [TwigContextFactoryCallable::class, 'create'];
        $operation = new Show(twigContextFactory: $twigContextFactory);
        $context = new Context();

        $result = $this->contextFactory->create($data, $operation, $context);

        $this->assertSame(['foo' => 'bar'], $result);
    }

    public function testItCreatesTwigContextFromOperationTwigContextFactoryAsString(): void
    {
        $data = new \stdClass();
        $twigContextFactory = $this->prophesize(ContextFactoryInterface::class);
        $operation = new Show(twigContextFactory: $twigContextFactory::class);
        $context = new Context();

        $this->locator->has($twigContextFactory::class)->willReturn(true);
        $this->locator->get($twigContextFactory::class)->willReturn($twigContextFactory->reveal());

        $twigContextFactory->create($data, $operation, $context)->willReturn(['foo' => 'bar']);

        $result = $this->contextFactory->create($data, $operation, $context);

        $this->assertSame(['foo' => 'bar'], $result);
    }

    public function testItThrowsExceptionWhenTwigContextFactoryNotFoundOnLocator(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Twig context factory "%s" not found on operation "app_dummy_show"', ContextFactoryInterface::class));

        $data = new \stdClass();
        $operation = new Show(name: 'app_dummy_show', twigContextFactory: ContextFactoryInterface::class);
        $context = new Context();

        $this->locator->has(ContextFactoryInterface::class)->willReturn(false);

        $this->contextFactory->create($data, $operation, $context);
    }
}

final class TwigContextFactoryCallable
{
    public static function create(mixed $data, Operation $operation, Context $context): array
    {
        return ['foo' => 'bar'];
    }
}
