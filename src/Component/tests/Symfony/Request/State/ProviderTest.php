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

namespace Sylius\Resource\Tests\Symfony\Request\State;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\CreatePaginatorTrait;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Exception\InvalidArgumentException;
use Sylius\Resource\Exception\RuntimeException;
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
    private ContainerInterface $locator;

    private ArgumentParserInterface $argumentParser;

    private Provider $provider;

    protected function setUp(): void
    {
        $this->locator = $this->createMock(ContainerInterface::class);
        $this->argumentParser = $this->createMock(ArgumentParserInterface::class);
        $this->provider = new Provider($this->locator, new RepositoryArgumentResolver(), $this->argumentParser);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Provider::class, $this->provider);
    }

    public function testItCallsRepositoryAsCallable(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createRequest(['id' => 'my_id'], [], []);

        $operation->method('getRepository')->willReturn([RepositoryWithCallables::class, 'find']);
        $operation->method('getRepositoryArguments')->willReturn(null);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertInstanceOf(\stdClass::class, $response);
        $this->assertSame('my_id', $response->id);
    }

    public function testItCallsRepositoryAsString(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createRequest(['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']], [], []);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn(null);
        $operation->method('getRepositoryArguments')->willReturn(null);

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('findOneBy')->with(['id' => 'my_id'])->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertSame($stdClass, $response);
    }

    public function testItCallsCreatePaginatorByDefaultOnCollectionOperations(): void
    {
        $operation = new Index(repository: 'App\Repository');
        $request = $this->createRequest(['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']], [], []);
        $repository = $this->createMock(RepositoryInterface::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->expects($this->once())->method('createPaginator')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(1)->willReturnSelf();

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertSame($pagerfanta, $response);
    }

    public function testItSetsCurrentPageFromRequestWhenDataIsAPaginator(): void
    {
        $operation = new Index(repository: 'App\Repository');
        $request = $this->createRequest(['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']], ['page' => 42], []);
        $repository = $this->createMock(RepositoryInterface::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->expects($this->once())->method('createPaginator')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(42)->willReturnSelf();
        $pagerfanta->method('getCurrentPage')->willReturn(42);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertSame($pagerfanta, $response);
        $this->assertSame(42, $pagerfanta->getCurrentPage());
    }

    public function testItCallsRepositoryAsStringWithSpecificRepositoryMethod(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createRequest(['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']], [], []);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('find');
        $operation->method('getRepositoryArguments')->willReturn(null);

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('find')->with('my_id')->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertSame($stdClass, $response);
    }

    public function testItCallsRepositoryAsStringWithSpecificRepositoryMethodAndArguments(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('find');
        $operation->method('getRepositoryArguments')->willReturn(['id' => "request.attributes.get('id')"]);

        $this->argumentParser
            ->expects($this->once())
            ->method('parseExpression')
            ->with("request.attributes.get('id')")
            ->willReturn('my_id');

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('find')->with('my_id')->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertSame($stdClass, $response);
    }

    public function testItCallsRepositoryAsStringWithSpecificRepositoryMethodAndExpressionLanguagePrefixedArguments(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('find');
        $operation->method('getRepositoryArguments')->willReturn(['id' => "@=request.attributes.get('id')"]);

        $this->argumentParser
            ->expects($this->once())
            ->method('parseExpression')
            ->with("request.attributes.get('id')")
            ->willReturn('my_id');

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('find')->with('my_id')->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertSame($stdClass, $response);
    }

    public function testItParsesNestedArrayArguments(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('findOneBy');
        $operation->method('getRepositoryArguments')->willReturn([['tokenValue' => "@=request.attributes.get('tokenValue')"]]);

        $this->argumentParser
            ->expects($this->once())
            ->method('parseExpression')
            ->with("request.attributes.get('tokenValue')")
            ->willReturn('my_token');

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $repository->method('findOneBy')->with(['tokenValue' => 'my_token'])->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));

        $this->assertSame($stdClass, $response);
    }

    public function testItThrowsAnExceptionWhenRepositoryMethodDoesNotExist(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('notFoundMethod');
        $operation->method('getRepositoryArguments')->willReturn(['id' => "request.attributes.get('id')"]);

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn(new \stdClass());

        $errorMessage = sprintf(
            'Method "notFoundMethod" not found on repository "%s". You can either add it or configure another one in the repositoryMethod option for your operation.',
            \stdClass::class,
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->provider->provide($operation, new Context(new RequestOption($request)));
    }

    public function testItThrowsAnExceptionWhenRepositoryMethodDoesNotExistAndSuggestToUseCreatePaginatorIfItIsAppropriated(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('createPaginator');
        $operation->method('getRepositoryArguments')->willReturn(['id' => "request.attributes.get('id')"]);

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn(new \stdClass());

        $errorMessage = sprintf(
            'Method "createPaginator" not found on repository "%s". You can use the "%s" trait on this repository class.',
            \stdClass::class,
            CreatePaginatorTrait::class,
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->provider->provide($operation, new Context(new RequestOption($request)));
    }

    public function testItThrowsAnExceptionWhenRepositoryArgumentsAreNotScalarOnes(): void
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RepositoryInterface::class);

        $operation->method('getRepository')->willReturn('App\Repository');
        $operation->method('getRepositoryMethod')->willReturn('findOneBy');
        $operation->method('getRepositoryArguments')->willReturn([['foo' => 'resource.code', 'bar' => new \stdClass()]]);

        $this->locator->method('has')->with('App\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\Repository')->willReturn($repository);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "bar" should be a scalar or an array.');

        $this->provider->provide($operation, new Context(new RequestOption($request)));
    }

    private function createRequest(array $routeParams, array $queryParams, array $requestParams): Request
    {
        $request = new Request();
        $request->attributes = new ParameterBag(['_route_params' => $routeParams]);
        $request->query = new InputBag($queryParams);
        $request->request = new InputBag($requestParams);

        return $request;
    }
}
