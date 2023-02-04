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

namespace spec\Sylius\Component\Resource\Symfony\Request;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Reflection\CallableReflection;
use Sylius\Component\Resource\Symfony\Request\RepositoryArgumentResolver;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class RepositoryArgumentResolverSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(RepositoryArgumentResolver::class);
    }

    function it_gets_arguments_to_sent_to_the_repository(
        Request $request,
        ParameterBag $attributes,
    ): void {
        $request->attributes = $attributes;
        $request->query = new InputBag([]);

        $attributes->all('_route_params')->willReturn(['id' => 'my_id']);

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $this->getArguments($request, $reflector)->shouldReturn([
            'id' => 'my_id',
        ]);
    }

    function it_merges_arguments_from_route_params_and_query_params(
        Request $request,
        ParameterBag $attributes,
    ): void {
        $request->attributes = $attributes;
        $request->query = new InputBag(['id' => 'my_id']);

        $attributes->all('_route_params')->willReturn(['_sylius' => ['resource' => 'app.dummy']]);

        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $this->getArguments($request, $reflector)->shouldReturn([
            'id' => 'my_id',
        ]);
    }

    function it_encapsulates_arguments_when_the_method_has_only_one_required_array_argument(
        Request $request,
        ParameterBag $attributes,
    ): void {
        $request->attributes = $attributes;
        $request->query = new InputBag([]);

        $attributes->all('_route_params')->willReturn(['enabled' => 'true', 'author' => 'author@example.com']);

        $callable = [RepositoryWithCallables::class, 'findOneBy'];
        $reflector = CallableReflection::from($callable);

        $this->getArguments($request, $reflector)->shouldReturn([
            [
                'enabled' => 'true',
                'author' => 'author@example.com',
            ],
        ]);
    }
}
