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
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\Routing\ArgumentParser;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandler;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandlerSpec extends ObjectBehavior
{
    function let(RouterInterface $router): void
    {
        $this->beConstructedWith($router, new ArgumentParser(
            new ExpressionLanguage(),
        ));
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RedirectHandler::class);
    }

    function it_redirects_to_resource_with_id_argument_by_default(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $data->id = 'xyz';
        $operation = new Create(redirectToRoute: 'app_dummy_index');
        $resource = new Resource(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $router->generate('app_dummy_index', ['id' => 'xyz'])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_with_id_via_property_access(
        Request $request,
        RouterInterface $router,
    ): void {
        $data = new BoardGame('uid');
        $operation = new Create(redirectToRoute: 'app_board_game_index');
        $resource = new Resource(alias: 'app.board_game');
        $operation = $operation->withResource($resource);

        $router->generate('app_board_game_index', ['id' => 'uid'])->willReturn('/board-games')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_with_custom_arguments(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $data->code = 'xyz';
        $operation = new Create(redirectToRoute: 'app_dummy_index', redirectArguments: ['code' => 'resource.code']);
        $resource = new Resource(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $router->generate('app_dummy_index', ['code' => 'xyz'])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_without_arguments_after_delete_operation_by_default(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $data->id = 'xyz';
        $operation = new Delete(redirectToRoute: 'app_dummy_index');
        $resource = new Resource(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $router->generate('app_dummy_index', [])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_route(
        \stdClass $data,
        RouterInterface $router,
    ): void {
        $router->generate('app_dummy_index', [])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToRoute($data, 'app_dummy_index');
    }

    function it_throws_an_exception_when_operation_has_no_resource(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $operation = new Create(redirectToRoute: 'app_dummy_index', name: 'app_dummy_create');

        $router->generate(Argument::cetera())->shouldNotBeCalled();

        $this->shouldThrow(
            new \RuntimeException('Operation "app_dummy_create" has no resource, but it should.'),
        )->during('redirectToResource', [$data, $operation, $request]);
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

final class BoardGame
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
