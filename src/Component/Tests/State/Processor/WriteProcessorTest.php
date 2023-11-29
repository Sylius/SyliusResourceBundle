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

namespace spec\Sylius\Resource\Symfony\EventListener;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Resource\src\State\Processor\WriteProcessor;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class WriteProcessorTest extends TestCase
{
    use ProphecyTrait;

    private ProcessorInterface|ObjectProphecy $decorated;

    private ProcessorInterface|ObjectProphecy $processor;

    private WriteProcessor $writeProcessor;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProcessorInterface::class);
        $this->processor = $this->prophesize(ProcessorInterface::class);

        $this->writeProcessor = new WriteProcessor(
            $this->decorated->reveal(),
            $this->processor->reveal(),
        );
    }

    /** @test */
    public function it_writes_data(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);
        $persistResult = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();

        $this->processor->process(['foo' => 'fighters'], $operation, $context)->willReturn($persistResult)->shouldBeCalled();

        $result = $this->writeProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
        Assert::eq($result, $persistResult->reveal());
    }

    /** @test */
    public function it_does_nothing_when_data_is_a_response(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);
        $response = $this->prophesize(Response::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn($response)->shouldBeCalled();

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST')->shouldNotBeCalled();
        $request->isMethodSafe()->willReturn(false)->shouldNotBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldNotBeCalled();

        $this->processor->process(Argument::any())->shouldNotBeCalled();

        $result = $this->writeProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);

        Assert::eq($result, $response->reveal());
    }

    /** @test */
    public function it_returns_null_on_delete_method(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('DELETE')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->processor->process(['foo' => 'fighters'], $operation, $context)->willReturn('persisted_result')->shouldBeCalled();

        $result = $this->writeProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);

        Assert::null($result);
    }

    /** @test */
    public function it_does_nothing_when_operation_cannot_be_written(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $operation->canWrite()->willReturn(false)->shouldBeCalled();

        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(false)->shouldNotBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldNotBeCalled();

        $this->processor->process(Argument::any())->shouldNotBeCalled();

        $this->writeProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_method_is_safe(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldNotBeCalled();

        $this->processor->process(Argument::any())->shouldNotBeCalled();

        $this->writeProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
    }
}
