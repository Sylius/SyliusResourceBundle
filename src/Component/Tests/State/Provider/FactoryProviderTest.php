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

namespace State\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Resource\src\State\Provider\FactoryProvider;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\State\FactoryInterface;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class FactoryProviderTest extends TestCase
{
    use ProphecyTrait;

    private ProviderInterface|ObjectProphecy $decorated;

    private FactoryInterface|ObjectProphecy $factory;

    private FactoryProvider $factoryProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProviderInterface::class);
        $this->factory = $this->prophesize(FactoryInterface::class);

        $this->factoryProvider = new FactoryProvider(
            $this->decorated->reveal(),
            $this->factory->reveal(),
        );
    }

    /** @test */
    public function it_uses_factory_from_operation(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->attributes = $attributes;

        $this->factory->create($operation, $context)->willReturn($data)->shouldBeCalled();

        $attributes->set('data', $data)->shouldBeCalled();

        $this->factoryProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_not_store_data_on_request_when_it_does_not_exist(): void
    {
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create();

        $context = new Context();

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $this->factory->create($operation, $context)->willReturn($data)->shouldBeCalled();

        $attributes->set('data', $data)->shouldNotBeCalled();

        $this->factoryProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_not_a_factory_aware_operation(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Update();

        $context = new Context();

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->attributes = $attributes;

        $this->factory->create($operation, $context)->willReturn($data)->shouldNotBeCalled();

        $attributes->set('data', $data)->shouldNotBeCalled();

        $this->factoryProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_factory_is_disabled(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create(factory: false);

        $context = new Context();

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->attributes = $attributes;

        $this->factory->create($operation, $context)->willReturn($data)->shouldNotBeCalled();

        $attributes->set('data', $data)->shouldNotBeCalled();

        $this->factoryProvider->provide($operation, $context);
    }
}
