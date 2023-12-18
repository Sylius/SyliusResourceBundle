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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\Processor\FlashProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FlashProcessorTest extends TestCase
{
    use ProphecyTrait;

    private ProcessorInterface|ObjectProphecy $decorated;

    private FlashHelperInterface|ObjectProphecy $flashHelper;

    private FlashProcessor $flashProcessor;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProcessorInterface::class);
        $this->flashHelper = $this->prophesize(FlashHelperInterface::class);

        $this->flashProcessor = new FlashProcessor(
            $this->decorated->reveal(),
            $this->flashHelper->reveal(),
        );
    }

    /** @test */
    public function it_adds_flash(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);

        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false)->shouldBeCalled();

        $operation->canWrite()->willReturn(null)->shouldBeCalled();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldBeCalled();

        $this->flashProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_when_controller_result_is_a_response(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $response = $this->prophesize(Response::class);

        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false)->shouldNotBeCalled();

        $operation->canWrite()->willReturn(null)->shouldNotBeCalled();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process($response, $operation, $context)->willReturn($response)->shouldBeCalled();

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->flashProcessor->process($response->reveal(), $operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_when_method_is_safe(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);

        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $operation->canWrite()->willReturn(null)->shouldNotBeCalled();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->flashProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_cannot_be_written(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);

        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false);

        $operation->canWrite()->willReturn(false)->shouldBeCalled();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->flashProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
    }
}
