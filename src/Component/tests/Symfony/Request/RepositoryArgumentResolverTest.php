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

namespace Tests\Sylius\Resource\Symfony\Request;

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

    public function testIsInitializable(): void
    {
        $this->assertInstanceOf(RepositoryArgumentResolver::class, $this->repositoryArgumentResolver);
    }

    public function testGetsArgumentsToSendToTheRepository(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $request->attributes = $attributes;
        $request->query = new InputBag([]);
        $request->request = new InputBag([]);

        $attributes->method('all')->with('_route_params')->willReturn(['id' => 'my_id']);

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $this->assertSame(['id' => 'my_id'], $this->repositoryArgumentResolver->getArguments($request, $reflector));
    }

    public function testUsesQueryParamsWhenRouteParamsAreNotMatching(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $request->attributes = $attributes;
        $request->query = new InputBag(['id' => 'my_id']);
        $request->request = new InputBag([]);

        $attributes->method('all')->with('_route_params')->willReturn(['_sylius' => ['resource' => 'app.dummy']]);

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $this->assertSame(['id' => 'my_id'], $this->repositoryArgumentResolver->getArguments($request, $reflector));
    }

    public function testUsesRequestParamsWhenRouteParamsAreNotMatching(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $request->attributes = $attributes;
        $request->query = new InputBag([]);
        $request->request = new InputBag(['id' => 'my_id']);

        $attributes->method('all')->with('_route_params')->willReturn(['_sylius' => ['resource' => 'app.dummy']]);

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $this->assertSame(['id' => 'my_id'], $this->repositoryArgumentResolver->getArguments($request, $reflector));
    }

    public function testEncapsulatesArgumentsWhenTheMethodHasOnlyOneRequiredArrayArgument(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $request->attributes = $attributes;
        $request->query = new InputBag([]);
        $request->request = new InputBag([]);

        $attributes->method('all')->with('_route_params')->willReturn(['enabled' => 'true', 'author' => 'author@example.com']);

        $callable = [RepositoryWithCallables::class, 'findOneBy'];
        $reflector = CallableReflection::from($callable);

        $this->assertSame([['enabled' => 'true', 'author' => 'author@example.com']], $this->repositoryArgumentResolver->getArguments($request, $reflector));
    }

    public function testReturnArrayValuesWhenMethodIsMagic(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $request->attributes = $attributes;
        $request->query = new InputBag([]);
        $request->request = new InputBag(['ids' => ['first_id', 'second_id']]);

        $attributes->method('all')->with('_route_params')->willReturn(['_sylius' => ['resource' => 'app.dummy']]);

        $callable = [new RepositoryWithCallables(), '__call'];
        $reflector = CallableReflection::from($callable);

        $this->assertSame([['first_id', 'second_id']], $this->repositoryArgumentResolver->getArguments($request, $reflector));
    }
}
