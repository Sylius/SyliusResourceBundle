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

namespace spec\Sylius\Resource\Symfony\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteNameFactoryInterface;
use Sylius\Resource\Symfony\Routing\RedirectHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandlerSpec extends ObjectBehavior
{
    function let(
        RouterInterface $router,
        ArgumentParserInterface $argumentParser,
        OperationRouteNameFactoryInterface $operationRouteNameFactory,
    ): void {
        $this->beConstructedWith(
            $router,
            $argumentParser,
            $operationRouteNameFactory,
        );
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
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $router->generate('app_dummy_index', ['id' => 'xyz'])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_with_custom_identifier_argument_by_default(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $data->code = 'xyz';
        $operation = new Create(redirectToRoute: 'app_dummy_index');
        $resource = new ResourceMetadata(alias: 'app.ok', identifier: 'code');
        $operation = $operation->withResource($resource);

        $router->generate('app_dummy_index', ['code' => 'xyz'])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_with_id_via_property_access(
        Request $request,
        RouterInterface $router,
    ): void {
        $data = new BoardGameResource('uid');
        $operation = new Create(redirectToRoute: 'app_board_game_index');
        $resource = new ResourceMetadata(alias: 'app.board_game');
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
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $router->generate('app_dummy_index', ['code' => 'xyz'])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_with_id_via_the_getter(
        Request $request,
        RouterInterface $router,
        ArgumentParserInterface $argumentParser,
    ): void {
        $data = new BoardGameResource('uid');
        $operation = new Create(redirectToRoute: 'app_board_game_index', redirectArguments: ['id' => 'resource.id()']);
        $resource = new ResourceMetadata(alias: 'app.board_game');
        $operation = $operation->withResource($resource);

        $argumentParser->parseExpression('resource.id()', ['resource' => $data])->willReturn('uid');

        $router->generate('app_board_game_index', ['id' => 'uid'])->willReturn('/board-games')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_without_arguments_after_delete_operation_by_default(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $data->id = 'xyz';
        $operation = new Delete(redirectToRoute: 'app_dummy_index');
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $router->generate('app_dummy_index', [])->willReturn('/dummies')->shouldBeCalled();

        $this->redirectToResource($data, $operation, $request);
    }

    function it_redirects_to_resource_without_arguments_after_bulk_operation_by_default(
        \stdClass $data,
        Request $request,
        RouterInterface $router,
    ): void {
        $data->id = 'xyz';
        $operation = new BulkUpdate(redirectToRoute: 'app_dummy_index');
        $resource = new ResourceMetadata(alias: 'app.book');
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

final class BoardGameResource
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
