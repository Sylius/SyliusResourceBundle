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

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
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
    use ProphecyTrait;

    private ObjectProphecy|HttpOperationInitiatorInterface $operationInitiator;

    private ObjectProphecy|RequestContextInitiatorInterface $requestContextInitiator;

    private ObjectProphecy|ProviderInterface $provider;

    private ObjectProphecy|ProcessorInterface $processor;

    private MainController $mainController;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->requestContextInitiator = $this->prophesize(RequestContextInitiatorInterface::class);
        $this->provider = $this->prophesize(ProviderInterface::class);
        $this->processor = $this->prophesize(ProcessorInterface::class);

        $this->mainController = new MainController(
            $this->operationInitiator->reveal(),
            $this->requestContextInitiator->reveal(),
            $this->provider->reveal(),
            $this->processor->reveal(),
        );
    }

    /** @test */
    public function it_returns_a_response(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = new Create();
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->initializeOperation($request)->willReturn($operation)->shouldBeCalled();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context)->shouldBeCalled();

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->provider->provide(Argument::cetera())->willReturn($data)->shouldBeCalled();
        $this->processor->process(Argument::cetera())->willReturn($response)->shouldBeCalled();

        $result = $this->mainController->__invoke($request->reveal());
        $this->assertEquals($response, $result);
    }

    /** @test */
    public function it_throws_an_exception_when_operation_is_null(): void
    {
        $request = $this->prophesize(Request::class);

        $this->operationInitiator->initializeOperation($request)->willReturn(null)->shouldBeCalled();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation should not be null.');

        $this->mainController->__invoke($request->reveal());
    }

    /** @test */
    public function it_disables_write_if_http_method_is_safe(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->initializeOperation($request)->willReturn($operation)->shouldBeCalled();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context)->shouldBeCalled();

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->provider->provide(Argument::cetera())->willReturn($data)->shouldBeCalled();
        $this->processor->process(Argument::cetera())->willReturn($response)->shouldBeCalled();

        $operation->canWrite()->willReturn(null)->shouldBeCalled();
        $operation->withWrite(false)->shouldBeCalled();

        $this->mainController->__invoke($request->reveal());
    }

    /** @test */
    public function it_disables_write_if_validation_has_failed(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->initializeOperation($request)->willReturn($operation)->shouldBeCalled();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(false);

        $this->provider->provide(Argument::cetera())->willReturn($data)->shouldBeCalled();
        $this->processor->process(Argument::cetera())->willReturn($response)->shouldBeCalled();

        $operation->canWrite()->willReturn(true)->shouldBeCalled();
        $operation->withWrite(false)->willReturn($operation)->shouldBeCalled();

        $this->mainController->__invoke($request->reveal());
    }

    /** @test */
    public function it_does_not_enable_write_if_validation_is_ok(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);
        $context = new Context();
        $response = new Response();
        $data = new \stdClass();

        $request->attributes = $attributes;

        $this->operationInitiator->initializeOperation($request)->willReturn($operation)->shouldBeCalled();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->provider->provide(Argument::cetera())->willReturn($data)->shouldBeCalled();
        $this->processor->process(Argument::cetera())->willReturn($response)->shouldBeCalled();

        $operation->canWrite()->willReturn(false)->shouldBeCalled();
        $operation->withWrite(true)->shouldNotBeCalled();

        $this->mainController->__invoke($request->reveal());
    }
}
