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

namespace Sylius\Resource\Tests\State\Provider;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\State\FactoryInterface;
use Sylius\Resource\State\Provider\FactoryProvider;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class FactoryProviderTest extends TestCase
{
    private ProviderInterface|MockObject $decorated;

    private FactoryInterface|MockObject $factory;

    private FactoryProvider $factoryProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ProviderInterface::class);
        $this->factory = $this->createMock(FactoryInterface::class);

        $this->factoryProvider = new FactoryProvider(
            $this->decorated,
            $this->factory,
        );
    }

    /** @test */
    public function it_uses_factory_from_operation(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $request->attributes = $attributes;

        $this->factory->expects($this->once())
            ->method('create')
            ->with($operation, $context)
            ->willReturn($data)
        ;

        $attributes->expects($this->once())
            ->method('set')
            ->with('data', $data)
        ;

        $this->factoryProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_not_store_data_on_request_when_it_does_not_exist(): void
    {
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Create();

        $context = new Context();

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $this->factory->expects($this->once())
            ->method('create')
            ->with($operation, $context)
            ->willReturn($data)
        ;

        $attributes->expects($this->never())->method('set');

        $this->factoryProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_not_a_factory_aware_operation(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Update();

        $context = new Context();

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $request->attributes = $attributes;

        $this->factory->expects($this->never())->method('create');
        $attributes->expects($this->never())->method('set');

        $this->factoryProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_factory_is_disabled(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Create(factory: false);

        $context = new Context();

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $request->attributes = $attributes;

        $this->factory->expects($this->never())->method('create');
        $attributes->expects($this->never())->method('set');

        $this->factoryProvider->provide($operation, $context);
    }
}
