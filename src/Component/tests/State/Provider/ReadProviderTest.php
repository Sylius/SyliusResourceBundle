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
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\Provider\ReadProvider;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadProviderTest extends TestCase
{
    private ProviderInterface|MockObject $provider;

    private ReadProvider $readProvider;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(ProviderInterface::class);

        $this->readProvider = new ReadProvider(
            $this->provider,
        );
    }

    /** @test */
    public function it_retrieves_data(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context();

        $request->attributes = $attributes;

        $this->provider->expects($this->once())->method('provide')->with($operation, $this->isInstanceOf(Context::class))->willReturn(['foo' => 'fighters']);

        $attributes->expects($this->never())->method('set');

        $this->readProvider->provide($operation, $context);
    }

    /** @test */
    public function it_retrieves_data_and_store_them_to_request(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $this->provider->expects($this->once())->method('provide')->with($operation, $this->isInstanceOf(Context::class))->willReturn(['foo' => 'fighters']);

        $attributes->expects($this->once())->method('set')->with('data', ['foo' => 'fighters']);

        $this->readProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_a_create_operation(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $this->provider->expects($this->never())->method('provide');

        $attributes->expects($this->never())->method('set');

        $this->readProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_cannot_be_read(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $operation->method('canRead')->willReturn(false);

        $this->provider->expects($this->never())->method('provide');

        $attributes->expects($this->never())->method('set');

        $this->readProvider->provide($operation, $context);
    }

    /** @test */
    public function it_throws_an_exception_when_no_data_was_found(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $operation->method('canRead')->willReturn(true);

        $this->provider->expects($this->once())->method('provide')->with($operation, $this->isInstanceOf(Context::class))->willReturn(null);

        $attributes->expects($this->never())->method('set');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Resource has not been found.');

        $this->readProvider->provide($operation, $context);
    }
}
