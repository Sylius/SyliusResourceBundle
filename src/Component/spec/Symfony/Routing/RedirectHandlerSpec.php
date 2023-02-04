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

namespace spec\Sylius\Component\Resource\Symfony\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandlerSpec extends ObjectBehavior
{
    function let(RouterInterface $router): void
    {
        $this->beConstructedWith($router);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RedirectHandler::class);
    }

    function it_redirects_to_resource(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $operation = new Create(redirectToRoute: 'app_dummy_index');

        $router->generate('app_dummy_index', [])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_route(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $operation = new Create();

        $router->generate('app_dummy_index', [])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToRoute($data, $operation, 'app_dummy_index');
    }

    function it_throws_an_exception_when_operation_has_no_route_redirection(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $operation = new Create(name: 'app_dummy_create');

        $router->generate(Argument::cetera())->shouldNotBeCalled();

        $this->shouldThrow(
            new \RuntimeException('Operation "app_dummy_create" has no redirection route, but it should.'),
        )->during('redirectToResource', [$data, $operation, $request]);
    }
}
