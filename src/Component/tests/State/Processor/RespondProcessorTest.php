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
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\Processor\RespondProcessor;
use Sylius\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class RespondProcessorTest extends TestCase
{
    private RequestContextInitiatorInterface|MockObject $contextInitiator;

    private ResponderInterface|MockObject $responder;

    private RespondProcessor $respondProcessor;

    protected function setUp(): void
    {
        $this->responder = $this->createMock(ResponderInterface::class);

        $this->respondProcessor = new RespondProcessor(
            $this->responder,
        );
    }

    /** @test */
    public function it_returns_a_response(): void
    {
        $response = $this->createMock(Response::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context();

        $this->responder->expects($this->once())
            ->method('respond')
            ->with(['foo' => 'fighters'], $operation, $context)
            ->willReturn($response);

        $data = $this->respondProcessor->process(['foo' => 'fighters'], $operation, $context);
        Assert::eq($data, $response);
    }

    /** @test */
    public function it_does_nothing_when_data_is_a_response(): void
    {
        $response = $this->createMock(Response::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context();

        $this->responder->expects($this->never())
            ->method('respond');

        $data = $this->respondProcessor->process($response, $operation, $context);
        Assert::eq($data, $response);
    }
}
