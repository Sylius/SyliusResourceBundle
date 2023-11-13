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

namespace spec\Sylius\Resource\Symfony\Request\State;

use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Sylius\Resource\Symfony\Request\RepositoryArgumentResolver;
use Sylius\Resource\Symfony\Request\State\Provider;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class ProviderSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator, ArgumentParserInterface $argumentParser): void
    {
        $this->beConstructedWith($locator, new RepositoryArgumentResolver(), $argumentParser);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Provider::class);
    }

    function it_calls_repository_as_callable(
        Operation $operation,
        Request $request,
    ): void {
        $operation->getRepository()->willReturn([RepositoryWithCallables::class, 'find']);
        $operation->getRepositoryArguments()->willReturn(null);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id']]);
        $request->query = new InputBag([]);
        $request->request = new ParameterBag();

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldHaveType(\stdClass::class);
        $response->id->shouldReturn('my_id');
    }

    function it_calls_repository_as_string(
        Operation $operation,
        Request $request,
        ContainerInterface $locator,
        RepositoryInterface $repository,
        \stdClass $stdClass,
    ): void {
        $operation->getRepository()->willReturn('App\Repository');
        $operation->getRepositoryMethod()->willReturn(null);
        $operation->getRepositoryArguments()->willReturn(null);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);
        $request->request = new ParameterBag();

        $locator->has('App\Repository')->willReturn(true);
        $locator->get('App\Repository')->willReturn($repository);

        $repository->findOneBy(['id' => 'my_id'])->willReturn($stdClass);

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldReturn($stdClass);
    }

    function it_calls_create_paginator_by_default_on_collection_operations(
        Request $request,
        ContainerInterface $locator,
        RepositoryInterface $repository,
        Pagerfanta $pagerfanta,
    ): void {
        $operation = new Index(repository: 'App\Repository');

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);
        $request->request = new ParameterBag();

        $locator->has('App\Repository')->willReturn(true);
        $locator->get('App\Repository')->willReturn($repository);

        $repository->createPaginator()->willReturn($pagerfanta)->shouldBeCalled();
        $pagerfanta->setCurrentPage(1)->willReturn($pagerfanta)->shouldBeCalled();

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldReturn($pagerfanta);
    }

    function it_sets_current_page_from_request_when_data_is_a_paginator(
        Request $request,
        ContainerInterface $locator,
        RepositoryInterface $repository,
        Pagerfanta $pagerfanta,
    ): void {
        $operation = new Index(repository: 'App\Repository');

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag(['page' => 42]);
        $request->request = new ParameterBag();

        $locator->has('App\Repository')->willReturn(true);
        $locator->get('App\Repository')->willReturn($repository);

        $repository->createPaginator()->willReturn($pagerfanta)->shouldBeCalled();
        $pagerfanta->setCurrentPage(42)->willReturn($pagerfanta)->shouldBeCalled();

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldReturn($pagerfanta);
        $pagerfanta->getCurrentPage()->willReturn(42);
    }

    function it_calls_repository_as_string_with_specific_repository_method(
        Operation $operation,
        Request $request,
        ContainerInterface $locator,
        RepositoryInterface $repository,
        \stdClass $stdClass,
    ): void {
        $operation->getRepository()->willReturn('App\Repository');
        $operation->getRepositoryMethod()->willReturn('find');
        $operation->getRepositoryArguments()->willReturn(null);

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);
        $request->request = new ParameterBag();

        $locator->has('App\Repository')->willReturn(true);
        $locator->get('App\Repository')->willReturn($repository);

        $repository->find('my_id')->willReturn($stdClass);

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldReturn($stdClass);
    }

    function it_calls_repository_as_string_with_specific_repository_method_an_arguments(
        Operation $operation,
        Request $request,
        ContainerInterface $locator,
        RepositoryInterface $repository,
        ArgumentParserInterface $argumentParser,
        \stdClass $stdClass,
    ): void {
        $operation->getRepository()->willReturn('App\Repository');
        $operation->getRepositoryMethod()->willReturn('find');
        $operation->getRepositoryArguments()->willReturn(['id' => "request.attributes.get('id')"]);

        $argumentParser->parseExpression("request.attributes.get('id')")->willReturn('my_id');

        $locator->has('App\Repository')->willReturn(true);
        $locator->get('App\Repository')->willReturn($repository);

        $repository->find('my_id')->willReturn($stdClass);

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldReturn($stdClass);
    }
}
