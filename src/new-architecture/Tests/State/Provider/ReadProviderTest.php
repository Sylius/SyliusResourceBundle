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

namespace Sylius\Resource\Tests\State\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\Provider\ReadProvider;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadProviderTest extends TestCase
{
    use ProphecyTrait;

    private ProviderInterface|ObjectProphecy $provider;

    private ReadProvider $readProvider;

    protected function setUp(): void
    {
        $this->provider = $this->prophesize(ProviderInterface::class);

        $this->readProvider = new ReadProvider(
            $this->provider->reveal(),
        );
    }

    /** @test */
    public function it_retrieves_data(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context();

        $request->attributes = $attributes;

        $this->provider->provide($operation, Argument::type(Context::class))->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->readProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_retrieves_data_and_store_them_to_request(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = $attributes;

        $this->provider->provide($operation, Argument::type(Context::class))->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $attributes->set('data', ['foo' => 'fighters'])->shouldBeCalled();

        $this->readProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_a_create_operation(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = $attributes;

        $this->provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->readProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_cannot_be_read(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation->canRead()->willReturn(false);

        $this->provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->readProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_throws_an_exception_when_no_data_was_found(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = $attributes;

        $operation->canRead()->willReturn(true);

        $this->provider->provide($operation, Argument::type(Context::class))->willReturn(null);

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Resource has not been found.');

        $this->readProvider->provide($operation->reveal(), $context);
    }
}
