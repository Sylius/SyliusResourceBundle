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
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\Processor\RespondProcessor;
use Sylius\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class RespondProcessorTest extends TestCase
{
    use ProphecyTrait;

    private RequestContextInitiatorInterface|ObjectProphecy $contextInitiator;

    private ResponderInterface|ObjectProphecy $responder;

    private RespondProcessor $respondProcessor;

    protected function setUp(): void
    {
        $this->responder = $this->prophesize(ResponderInterface::class);

        $this->respondProcessor = new RespondProcessor(
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

        $data = $this->respondProcessor->process(['foo' => 'fighters'], $operation->reveal(), $context);
        Assert::eq($data, $response->reveal());
    }

    /** @test */
    public function it_does_nothing_when_data_is_a_response(): void
    {
        $response = $this->prophesize(Response::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context();

        $this->responder->respond(Argument::cetera())
            ->willReturn($response)
            ->shouldNotBeCalled()
        ;

        $data = $this->respondProcessor->process($response->reveal(), $operation->reveal(), $context);
        Assert::eq($data, $response->reveal());
    }
}
