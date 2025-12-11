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

namespace Sylius\Resource\Tests\State;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Tests\Dummy\ResponderWithCallable;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\Responder;
use Sylius\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;

final class ResponderTest extends TestCase
{
    private Responder $responder;

    private ContainerInterface|MockObject $locator;

    protected function setUp(): void
    {
        $this->locator = $this->createMock(ContainerInterface::class);
        $this->responder = new Responder($this->locator);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Responder::class, $this->responder);
    }

    public function testItCallsRespondMethodFromOperationResponderAsString(): void
    {
        $operation = new Create(responder: '\App\Responder');
        $context = new Context();
        $responder = $this->createMock(ResponderInterface::class);

        $this->locator->expects($this->once())
            ->method('has')
            ->with('\App\Responder')
            ->willReturn(true);
        $this->locator->expects($this->once())
            ->method('get')
            ->with('\App\Responder')
            ->willReturn($responder);

        $responder->expects($this->once())
            ->method('respond')
            ->with([], $operation, $context)
            ->willReturn('response_data');

        $result = $this->responder->respond([], $operation, $context);
        $this->assertEquals('response_data', $result);
    }

    public function testItCallsRespondMethodFromOperationResponderAsCallable(): void
    {
        $operation = new Create(responder: [ResponderWithCallable::class, 'respond']);
        $context = new Context();

        $result = $this->responder->respond([], $operation, $context);

        $this->assertInstanceOf(Response::class, $result);
    }

    public function testItReturnsNullIfOperationHasNoResponder(): void
    {
        $operation = new Create();
        $context = new Context();

        $result = $this->responder->respond([], $operation, $context);

        $this->assertNull($result);
    }

    public function testItThrowsExceptionWhenConfiguredResponderIsNotAResponderInstance(): void
    {
        $operation = new Create(responder: '\stdClass');
        $context = new Context();

        $this->locator->expects($this->once())
            ->method('has')
            ->with('\stdClass')
            ->willReturn(true);
        $this->locator->expects($this->once())
            ->method('get')
            ->with('\stdClass')
            ->willReturn(new \stdClass());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an instance of Sylius\Resource\State\ResponderInterface. Got: stdClass');

        $this->responder->respond([], $operation, $context);
    }
}
