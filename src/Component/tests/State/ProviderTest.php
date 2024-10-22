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

namespace Sylius\Resource\Tests\State;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Tests\Dummy\ProviderWithCallable;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\Provider;
use Sylius\Resource\State\ProviderInterface;

final class ProviderTest extends TestCase
{
    use ProphecyTrait;

    private Provider $provider;

    private ContainerInterface|ObjectProphecy $locator;

    protected function setUp(): void
    {
        $this->locator = $this->prophesize(ContainerInterface::class);
        $this->provider = new Provider($this->locator->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Provider::class, $this->provider);
    }

    public function testItCallsProvideMethodFromOperationProviderAsString(): void
    {
        $operation = new Create(provider: '\App\Provider');
        $context = new Context();
        $provider = $this->prophesize(ProviderInterface::class);

        $this->locator->has('\App\Provider')->willReturn(true);
        $this->locator->get('\App\Provider')->willReturn($provider->reveal());

        $provider->provide($operation, $context)->shouldBeCalled();

        $this->provider->provide($operation, $context);
    }

    public function testItCallsProvideMethodFromOperationProviderAsCallable(): void
    {
        $operation = new Create(provider: [ProviderWithCallable::class, 'provide']);
        $context = new Context();

        $result = $this->provider->provide($operation, $context);

        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testItReturnsNullIfOperationHasNoProvider(): void
    {
        $operation = new Create();
        $context = new Context();

        $result = $this->provider->provide($operation, $context);

        $this->assertNull($result);
    }

    public function testItThrowsExceptionWhenConfiguredProviderIsNotAProviderInstance(): void
    {
        $operation = new Create(provider: '\stdClass');
        $context = new Context();

        $this->locator->has('\stdClass')->willReturn(true);
        $this->locator->get('\stdClass')->willReturn(new \stdClass());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an instance of Sylius\Resource\State\ProviderInterface. Got: stdClass');

        $this->provider->provide($operation, $context);
    }
}
