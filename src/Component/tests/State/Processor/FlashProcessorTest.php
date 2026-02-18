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
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\Processor\FlashProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FlashProcessorTest extends TestCase
{
    private ProcessorInterface&MockObject $decorated;

    private FlashHelperInterface&MockObject $flashHelper;

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
        $request = new Request();
        $request->setMethod('POST');

        $operation = new Create();
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
        $request = new Request(attributes: ['error' => 'Cannot delete, the resource is in use.']);
        $request->setMethod('POST');

        $operation = new Create();

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
    public function it_does_not_add_success_flash_when_notification_message_is_disabled(): void
    {
        $request = new Request();
        $request->setMethod('POST');

        $operation = new Create(notificationEnabled: false);

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('process')
            ->with(['foo' => 'fighters'], $operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $this->flashHelper->expects($this->never())
            ->method('addSuccessFlash')
            ->with($operation, $context)
        ;

        $this->flashProcessor->process(['foo' => 'fighters'], $operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_controller_result_is_a_response(): void
    {
        $request = new Request();
        $request->setMethod('POST');

        $operation = new Create();

        $response = new Response();

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
        $request = new Request();

        $operation = new Create();
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
        $request = new Request();
        $request->setMethod('POST');

        $operation = new Create(write: false);

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
