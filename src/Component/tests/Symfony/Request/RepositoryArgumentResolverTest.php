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

namespace Sylius\Resource\Tests\Symfony\Request;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Reflection\CallableReflection;
use Sylius\Resource\Symfony\Request\RepositoryArgumentResolver;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class RepositoryArgumentResolverTest extends TestCase
{
    private RepositoryArgumentResolver $repositoryArgumentResolver;

    protected function setUp(): void
    {
        $this->repositoryArgumentResolver = new RepositoryArgumentResolver();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RepositoryArgumentResolver::class, $this->repositoryArgumentResolver);
    }

    public function testItGetsArgumentsToSentToTheRepository(): void
    {
        $request = $this->createRequest(['id' => 'my_id'], [], []);

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $result = $this->repositoryArgumentResolver->getArguments($request, $reflector);

        $this->assertSame(['id' => 'my_id'], $result);
    }

    public function testItUsesQueryParamsWhenRouteParamsAreNotMatching(): void
    {
        $request = $this->createRequest(
            ['_sylius' => ['resource' => 'app.dummy']],
            ['id' => 'my_id'],
            [],
        );

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $result = $this->repositoryArgumentResolver->getArguments($request, $reflector);

        $this->assertSame(['id' => 'my_id'], $result);
    }

    public function testItUsesRequestParamsWhenRouteParamsAreNotMatching(): void
    {
        $request = $this->createRequest(
            ['_sylius' => ['resource' => 'app.dummy']],
            [],
            ['id' => 'my_id'],
        );

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $result = $this->repositoryArgumentResolver->getArguments($request, $reflector);

        $this->assertSame(['id' => 'my_id'], $result);
    }

    public function testItEncapsulatesArgumentsWhenTheMethodHasOnlyOneRequiredArrayArgument(): void
    {
        $request = $this->createRequest(
            ['enabled' => 'true', 'author' => 'author@example.com'],
            [],
            [],
        );

        $callable = [RepositoryWithCallables::class, 'findOneBy'];
        $reflector = CallableReflection::from($callable);

        $result = $this->repositoryArgumentResolver->getArguments($request, $reflector);

        $this->assertSame([['enabled' => 'true', 'author' => 'author@example.com']], $result);
    }

    public function testItReturnArrayValuesWhenMethodIsMagic(): void
    {
        $request = $this->createRequest(
            ['_sylius' => ['resource' => 'app.dummy']],
            [],
            ['ids' => ['first_id', 'second_id']],
        );

        $callable = [new RepositoryWithCallables(), '__call'];
        $reflector = CallableReflection::from($callable);

        $result = $this->repositoryArgumentResolver->getArguments($request, $reflector);

        $this->assertSame([['first_id', 'second_id']], $result);
    }

    private function createRequest(array $routeParams, array $queryParams, array $requestParams): Request
    {
        $request = new Request();
        $request->attributes = new ParameterBag();
        $request->query = new InputBag($queryParams);
        $request->request = new InputBag($requestParams);

        $attributesMock = $this->createMock(ParameterBag::class);
        $attributesMock->method('all')->with('_route_params')->willReturn($routeParams);

        $request->attributes = $attributesMock;

        return $request;
    }
}
