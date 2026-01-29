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

namespace Sylius\Resource\Tests\State\Processor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\Processor\FlashProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FlashProcessorTest extends TestCase
{
    private ProcessorInterface|MockObject $decorated;

    private FlashHelperInterface|MockObject $flashHelper;

    private FlashProcessor $flashProcessor;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ProcessorInterface::class);
        $this->flashHelper = $this->createMock(FlashHelperInterface::class);

        $this->flashProcessor = new FlashProcessor(
            $this->decorated,
            $this->flashHelper,
        );
    }

    /** @test */
    public function it_adds_success_flash(): void
    {
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $request->attributes = new ParameterBag();
        $request->method('getRequestFormat')->willReturn('html');
        $request->method('isMethodSafe')->willReturn(false);

        $operation->method('canWrite')->willReturn(null);

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('process')
            ->with(['foo' => 'fighters'], $operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $this->flashHelper->expects($this->once())
            ->method('addSuccessFlash')
            ->with($operation, $context)
        ;

        $this->flashProcessor->process(['foo' => 'fighters'], $operation, $context);
    }

    /** @test */
    public function it_adds_error_flash(): void
    {
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $request->attributes = new ParameterBag(['error' => 'Cannot delete, the resource is in use.']);
        $request->method('getRequestFormat')->willReturn('html');
        $request->method('isMethodSafe')->willReturn(false);

        $operation->method('canWrite')->willReturn(null);

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('process')
            ->with(['foo' => 'fighters'], $operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $this->flashHelper->expects($this->once())
            ->method('addErrorFlash')
            ->with($operation, $context)
        ;

        $this->flashProcessor->process(['foo' => 'fighters'], $operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_controller_result_is_a_response(): void
    {
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);
        $response = $this->createMock(Response::class);

        $request->method('getRequestFormat')->willReturn('html');
        $request->expects($this->never())->method('isMethodSafe');

        $operation->expects($this->never())->method('canWrite');

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('process')
            ->with($response, $operation, $context)
            ->willReturn($response)
        ;

        $this->flashHelper->expects($this->never())->method('addSuccessFlash');

        $this->flashProcessor->process($response, $operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_method_is_safe(): void
    {
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $request->method('getRequestFormat')->willReturn('html');
        $request->method('isMethodSafe')->willReturn(true);

        $operation->expects($this->never())->method('canWrite');

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('process')
            ->with(['foo' => 'fighters'], $operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $this->flashHelper->expects($this->never())->method('addSuccessFlash');

        $this->flashProcessor->process(['foo' => 'fighters'], $operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_cannot_be_written(): void
    {
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $request->method('getRequestFormat')->willReturn('html');
        $request->method('isMethodSafe')->willReturn(false);

        $operation->method('canWrite')->willReturn(false);

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('process')
            ->with(['foo' => 'fighters'], $operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $this->flashHelper->expects($this->never())->method('addSuccessFlash');

        $this->flashProcessor->process(['foo' => 'fighters'], $operation, $context);
    }
}
