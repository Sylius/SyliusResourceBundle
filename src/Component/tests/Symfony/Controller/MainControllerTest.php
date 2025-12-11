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

namespace Sylius\Component\Resource\tests\Symfony\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Exception\RuntimeException;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Controller\MainController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MainControllerTest extends TestCase
{
    private HttpOperationInitiatorInterface|MockObject $operationInitiator;

    private RequestContextInitiatorInterface|MockObject $requestContextInitiator;

    private ProviderInterface|MockObject $provider;

    private ProcessorInterface|MockObject $processor;

    private MainController $mainController;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->createMock(HttpOperationInitiatorInterface::class);
        $this->requestContextInitiator = $this->createMock(RequestContextInitiatorInterface::class);
        $this->provider = $this->createMock(ProviderInterface::class);
        $this->processor = $this->createMock(ProcessorInterface::class);

        $this->mainController = new MainController(
            $this->operationInitiator,
            $this->requestContextInitiator,
            $this->provider,
            $this->processor,
        );
    }

    /** @test */
    public function it_returns_a_response(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = new Create();
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->expects($this->once())->method('initializeOperation')->with($request)->willReturn($operation);

        $this->requestContextInitiator->expects($this->once())->method('initializeContext')->with($request)->willReturn($context);

        $request->method('isMethodSafe')->willReturn(true);

        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(true);

        $this->provider->expects($this->once())->method('provide')->with($operation, $context)->willReturn($data);
        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($response);

        $result = $this->mainController->__invoke($request);
        $this->assertEquals($response, $result);
    }

    /** @test */
    public function it_throws_an_exception_when_operation_is_null(): void
    {
        $request = $this->createMock(Request::class);

        $this->operationInitiator->expects($this->once())->method('initializeOperation')->with($request)->willReturn(null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Operation should not be null.');

        $this->mainController->__invoke($request);
    }

    /** @test */
    public function it_disables_write_if_http_method_is_safe(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = $this->createMock(HttpOperation::class);
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->expects($this->once())->method('initializeOperation')->with($request)->willReturn($operation);

        $this->requestContextInitiator->expects($this->once())->method('initializeContext')->with($request)->willReturn($context);

        $request->method('isMethodSafe')->willReturn(true);

        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(true);

        $this->provider->expects($this->once())->method('provide')->with($operation, $context)->willReturn($data);
        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($response);

        $operation->method('canWrite')->willReturn(null);
        $operation->expects($this->once())->method('withWrite')->with(false)->willReturn($operation);

        $this->mainController->__invoke($request);
    }

    /** @test */
    public function it_disables_write_if_validation_has_failed(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = $this->createMock(HttpOperation::class);
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->expects($this->once())->method('initializeOperation')->with($request)->willReturn($operation);

        $this->requestContextInitiator->expects($this->once())->method('initializeContext')->with($request)->willReturn($context);

        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(false);

        $this->provider->expects($this->once())->method('provide')->with($operation, $context)->willReturn($data);
        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($response);

        $operation->method('canWrite')->willReturn(true);
        $operation->expects($this->once())->method('withWrite')->with(false)->willReturn($operation);

        $this->mainController->__invoke($request);
    }

    /** @test */
    public function it_does_not_enable_write_if_validation_is_ok(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $operation = $this->createMock(HttpOperation::class);
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->expects($this->once())->method('initializeOperation')->with($request)->willReturn($operation);

        $this->requestContextInitiator->expects($this->once())->method('initializeContext')->with($request)->willReturn($context);

        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(true);

        $this->provider->expects($this->once())->method('provide')->with($operation, $context)->willReturn($data);
        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($response);

        $operation->method('canWrite')->willReturn(false);
        $operation->expects($this->never())->method('withWrite');

        $this->mainController->__invoke($request);
    }
}
