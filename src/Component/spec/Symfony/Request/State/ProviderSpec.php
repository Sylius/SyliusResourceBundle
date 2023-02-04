<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Resource\Symfony\Request\State;

use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Symfony\Request\RepositoryArgumentResolver;
use Sylius\Component\Resource\Symfony\Request\State\Provider;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class ProviderSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator): void
    {
        $this->beConstructedWith($locator, new RepositoryArgumentResolver());
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
        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id']]);
        $request->query = new InputBag([]);

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

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);

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

        $locator->has('App\Repository')->willReturn(true);
        $locator->get('App\Repository')->willReturn($repository);

        $repository->createPaginator()->willReturn($pagerfanta);

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldReturn($pagerfanta);
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

        $request->attributes = new ParameterBag(['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);
        $request->query = new InputBag([]);

        $locator->has('App\Repository')->willReturn(true);
        $locator->get('App\Repository')->willReturn($repository);

        $repository->find('my_id')->willReturn($stdClass);

        $response = $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldReturn($stdClass);
    }
}
