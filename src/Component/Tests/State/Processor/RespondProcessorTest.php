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

namespace State\Processor;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Resource\src\State\Processor\RespondProcessor;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class RespondProcessorTest extends TestCase
{
    use ProphecyTrait;

    private ProcessorInterface|ObjectProphecy $decorated;

    private RequestContextInitiatorInterface|ObjectProphecy $contextInitiator;

    private ResponderInterface|ObjectProphecy $responder;

    private RespondProcessor $respondProcessor;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProcessorInterface::class);
        $this->responder = $this->prophesize(ResponderInterface::class);

        $this->respondProcessor = new RespondProcessor(
            $this->decorated->reveal(),
            $this->responder->reveal(),
        );
    }

    /** @test */
    public function it_returns_a_response(): void
    {
        $response = $this->prophesize(Response::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context();

        $this->responder->respond(['foo' => 'fighters'], $operation, $context)
            ->willReturn($response)
            ->shouldBeCalled()
        ;

        $this->decorated->process(['foo' => 'fighters'], $operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $data = $this->respondProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
        Assert::eq($data, $response->reveal());
    }

    /** @test */
    public function it_does_nothing_when_data_is_a_response(): void
    {
        $response = $this->prophesize(Response::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context();

        $this->decorated->process($response, $operation, $context)->willReturn($response)->shouldBeCalled();

        $this->responder->respond($response, $operation, $context)
            ->willReturn($response)
            ->shouldNotBeCalled()
        ;

        $data = $this->respondProcessor->process($response, $operation->reveal(), $context);
        Assert::eq($data, $response->reveal());
    }
}
