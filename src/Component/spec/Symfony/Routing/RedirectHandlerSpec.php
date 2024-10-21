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

namespace Sylius\Tests\Resource\Symfony\Routing;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactoryInterface;
use Sylius\Resource\Symfony\Routing\RedirectHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandlerTest extends TestCase
{
    private RouterInterface $router;

    private ArgumentParserInterface $argumentParser;

    private OperationRouteNameFactoryInterface $operationRouteNameFactory;

    private RedirectHandler $redirectHandler;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->argumentParser = $this->createMock(ArgumentParserInterface::class);
        $this->operationRouteNameFactory = $this->createMock(OperationRouteNameFactoryInterface::class);
        $this->redirectHandler = new RedirectHandler($this->router, $this->argumentParser, $this->operationRouteNameFactory);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RedirectHandler::class, $this->redirectHandler);
    }

    public function testItRedirectsToResourceWithIdArgumentByDefault(): void
    {
        $data = new \stdClass();
        $data->id = 'xyz';
        $operation = new Create(redirectToRoute: 'app_dummy_index');
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_dummy_index', ['id' => 'xyz'])
            ->willReturn('/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItRedirectsToResourceWithCustomIdentifierArgumentByDefault(): void
    {
        $data = new \stdClass();
        $data->code = 'xyz';
        $operation = new Create(redirectToRoute: 'app_dummy_index');
        $resource = new ResourceMetadata(alias: 'app.ok', identifier: 'code');
        $operation = $operation->withResource($resource);

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_dummy_index', ['code' => 'xyz'])
            ->willReturn('/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItRedirectsToResourceWithIdViaPropertyAccess(): void
    {
        $data = new BoardGameResource('uid');
        $operation = new Create(redirectToRoute: 'app_board_game_index');
        $resource = new ResourceMetadata(alias: 'app.board_game');
        $operation = $operation->withResource($resource);

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_board_game_index', ['id' => 'uid'])
            ->willReturn('/board-games');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItRedirectsToResourceWithCustomArguments(): void
    {
        $data = new \stdClass();
        $data->code = 'xyz';
        $operation = new Create(redirectToRoute: 'app_dummy_index', redirectArguments: ['code' => 'resource.code']);
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_dummy_index', ['code' => 'xyz'])
            ->willReturn('/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItRedirectsToResourceWithIdViaTheGetter(): void
    {
        $data = new BoardGameResource('uid');
        $operation = new Create(redirectToRoute: 'app_board_game_index', redirectArguments: ['id' => 'resource.id()']);
        $resource = new ResourceMetadata(alias: 'app.board_game');
        $operation = $operation->withResource($resource);

        $this->argumentParser->expects($this->once())
            ->method('parseExpression')
            ->with('resource.id()', ['resource' => $data])
            ->willReturn('uid');

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_board_game_index', ['id' => 'uid'])
            ->willReturn('/board-games');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItRedirectsToResourceWithoutArgumentsAfterDeleteOperationByDefault(): void
    {
        $data = new \stdClass();
        $data->id = 'xyz';
        $operation = new Delete(redirectToRoute: 'app_dummy_index');
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_dummy_index', [])
            ->willReturn('/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItRedirectsToResourceWithoutArgumentsAfterBulkOperationByDefault(): void
    {
        $data = new \stdClass();
        $data->id = 'xyz';
        $operation = new BulkUpdate(redirectToRoute: 'app_dummy_index');
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_dummy_index', [])
            ->willReturn('/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItRedirectsToRoute(): void
    {
        $data = new \stdClass();

        $this->router->expects($this->once())
            ->method('generate')
            ->with('app_dummy_index', [])
            ->willReturn('/dummies');

        $this->redirectHandler->redirectToRoute($data, 'app_dummy_index');
    }

    public function testItThrowsAnExceptionWhenOperationHasNoResource(): void
    {
        $data = new \stdClass();
        $operation = new Create(redirectToRoute: 'app_dummy_index', name: 'app_dummy_create');

        $this->router->expects($this->never())
            ->method('generate');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_create" has no resource, but it should.');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
    }

    public function testItThrowsAnExceptionWhenOperationHasNoRouteRedirection(): void
    {
        $data = new \stdClass();
        $operation = new Create(name: 'app_dummy_create');

        $this->router->expects($this->never())
            ->method('generate');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_create" has no redirection route, but it should.');

        $this->redirectHandler->redirectToResource($data, $operation, new Request());
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
