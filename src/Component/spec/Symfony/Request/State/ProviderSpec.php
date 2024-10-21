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

namespace Tests\Sylius\Resource\Symfony\Request\State;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Sylius\Resource\Symfony\Request\RepositoryArgumentResolver;
use Sylius\Resource\Symfony\Request\State\Provider;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class ProviderTest extends TestCase
{
    private Provider $provider;

    private ContainerInterface $locator;

    private ArgumentParserInterface $argumentParser;

    protected function setUp(): void
    {
        $this->locator = $this->createMock(ContainerInterface::class);
        $this->argumentParser = $this->createMock(ArgumentParserInterface::class);
        $this->provider = new Provider($this->locator, new RepositoryArgumentResolver(), $this->argumentParser);
    }

    public function testIsInitializable(): void
    {
        $this->assertInstanceOf(Provider::class, $this->provider);
    }

    public function testCallsRepositoryAsCallable(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);

        $operation->method('getRepository')->willReturn([RepositoryWithCallables::class, 'find']);
        $operation->method('getRepositoryArguments')->willReturn(null);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id']]);
        $request->query = new InputBag([]);
        $request->request = new InputBag();

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertInstanceOf(\stdClass::class, $response);
        $this->assertSame('my_id', $response->id);
    }

    public function testCallsRepositoryAsString(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn(null);
        $operation->method('getRepositoryArguments')->willReturn(null);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);
        $request->request = new InputBag();

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('findOneBy')->with(['id' => 'my_id'])->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertSame($stdClass, $response);
    }

    public function testCallsCreatePaginatorByDefaultOnCollectionOperations(): void
    {
        $request = $this->createMock(Request::class);
        $operation = new Index(repository: 'App\Repository');
        $repository = $this->createMock(RepositoryInterface::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);
        $request->request = new InputBag();

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('createPaginator')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(1)->willReturn($pagerfanta);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertSame($pagerfanta, $response);
    }

    public function testSetsCurrentPageFromRequestWhenDataIsAPaginator(): void
    {
        $request = $this->createMock(Request::class);
        $operation = new Index(repository: 'App\Repository');
        $repository = $this->createMock(RepositoryInterface::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag(['page' => 42]);
        $request->request = new InputBag();

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('createPaginator')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(42)->willReturn($pagerfanta);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertSame($pagerfanta, $response);
        $pagerfanta->method('getCurrentPage')->willReturn(42);
    }

    public function testCallsRepositoryAsStringWithSpecificRepositoryMethod(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('find');
        $operation->method('getRepositoryArguments')->willReturn(null);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);
        $request->request = new InputBag();

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('find')->with('my_id')->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertSame($stdClass, $response);
    }

    public function testCallsRepositoryAsStringWithSpecificRepositoryMethodAndArguments(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('find');
        $operation->method('getRepositoryArguments')->willReturn(['id' => "request.attributes.get('id')"]);

        $this->argumentParser->method('parseExpression')->with("request.attributes.get('id')")->willReturn('my_id');

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('find')->with('my_id')->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertSame($stdClass, $response);
    }
}
