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

namespace Sylius\Resource\Tests\Symfony\Routing;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\GridBundle\Storage\FilterStorageInterface;
use Sylius\Resource\Exception\InvalidArgumentException;
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

    private FilterStorageInterface $filterStorage;

    private RedirectHandler $redirectHandler;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->argumentParser = $this->createMock(ArgumentParserInterface::class);
        $this->operationRouteNameFactory = $this->createMock(OperationRouteNameFactoryInterface::class);
        $this->filterStorage = $this->createMock(FilterStorageInterface::class);

        $this->redirectHandler = new RedirectHandler(
            $this->router,
            $this->argumentParser,
            $this->operationRouteNameFactory,
            $this->filterStorage,
        );
    }

    private function createDataWithId(string $id = 'xyz'): \stdClass
    {
        $data = new \stdClass();
        $data->id = $id;

        return $data;
    }

    private function createDataWithCode(string $code = 'xyz'): \stdClass
    {
        $data = new \stdClass();
        $data->code = $code;

        return $data;
    }

    private function mockFilterStorage(array $filters = []): void
    {
        $this->filterStorage->method('all')->willReturn($filters);
    }

    private function mockRouter(string $route, array $parameters, string $returnUrl): void
    {
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with($route, $parameters)
            ->willReturn($returnUrl);
    }

    public function testItRedirectsToResourceWithIdArgumentByDefault(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Create(redirectToRoute: 'app_dummy_index'))
            ->withResource(new ResourceMetadata(alias: 'app.book'));
        $request = $this->createMock(Request::class);

        $this->mockFilterStorage();
        $this->mockRouter('app_dummy_index', ['id' => 'xyz'], '/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItRedirectsToResourceWithCustomIdentifierArgumentByDefault(): void
    {
        $data = $this->createDataWithCode();
        $operation = (new Create(redirectToRoute: 'app_dummy_index'))
            ->withResource(new ResourceMetadata(alias: 'app.ok', identifier: 'code'));
        $request = $this->createMock(Request::class);

        $this->mockFilterStorage();
        $this->mockRouter('app_dummy_index', ['code' => 'xyz'], '/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItRedirectsToResourceWithoutArgumentsAfterDeleteOperationByDefault(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Delete(redirectToRoute: 'app_dummy_index'))
            ->withResource(new ResourceMetadata(alias: 'app.book'));
        $request = $this->createMock(Request::class);

        $this->mockFilterStorage();
        $this->mockRouter('app_dummy_index', [], '/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItUsesFiltersFromGridStorageWhenRedirectingToAnIndexOperation(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Delete(redirectToRoute: 'app_dummy_index'))
            ->withResource(new ResourceMetadata(alias: 'app.book'));
        $request = $this->createMock(Request::class);

        $this->mockFilterStorage(['criteria' => ['enabled' => true]]);
        $this->mockRouter('app_dummy_index', ['criteria' => ['enabled' => true]], '/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItRedirectsToRoute(): void
    {
        $data = new \stdClass();

        $this->mockFilterStorage();
        $this->mockRouter('app_dummy_index', [], '/dummies');

        $this->redirectHandler->redirectToRoute($data, 'app_dummy_index');
    }

    public function testItThrowsAnExceptionWhenOperationHasNoResource(): void
    {
        $data = new \stdClass();
        $operation = new Create(redirectToRoute: 'app_dummy_index', name: 'app_dummy_create');
        $request = $this->createMock(Request::class);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_create" has no resource, but it should.');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItThrowsAnExceptionWhenOperationHasNoRouteRedirection(): void
    {
        $data = new \stdClass();
        $operation = new Create(name: 'app_dummy_create');
        $request = $this->createMock(Request::class);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_create" has no redirection route, but it should.');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItThrowsAnExceptionWhenTryingRedirectWithCustomArgumentsThatAreNotScalarOnes(): void
    {
        $data = new \stdClass();
        $data->code = 'xyz';

        $operation = new Create(
            redirectToRoute: 'app_dummy_index',
            redirectArguments: ['code' => 'resource.code', 'criteria' => ['foo' => 'resource.code', 'bar' => new \stdClass()]],
        );
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = $operation->withResource($resource);

        $request = $this->createMock(Request::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "bar" should be a scalar or an array.');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItRedirectsToOperationWithIdArgumentByDefault(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Create(name: 'app_dummy_create'))
            ->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $request = $this->createMock(Request::class);

        $this->operationRouteNameFactory
            ->expects($this->once())
            ->method('createRouteName')
            ->with($operation, 'show')
            ->willReturn('app_dummy_show');

        $this->mockFilterStorage();
        $this->mockRouter('app_dummy_show', ['id' => 'xyz'], '/dummies/xyz');

        $response = $this->redirectHandler->redirectToOperation($data, $operation, $request, 'show');

        $this->assertSame('/dummies/xyz', $response->getTargetUrl());
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testItRedirectsToOperationWithCustomIdentifierArgumentByDefault(): void
    {
        $data = $this->createDataWithCode('ABC123');
        $operation = (new Create(name: 'app_product_create'))
            ->withResource(new ResourceMetadata(alias: 'app.product', name: 'product', applicationName: 'app', identifier: 'code'));
        $request = $this->createMock(Request::class);

        $this->operationRouteNameFactory
            ->expects($this->once())
            ->method('createRouteName')
            ->with($operation, 'update')
            ->willReturn('app_product_update');

        $this->mockFilterStorage();
        $this->mockRouter('app_product_update', ['code' => 'ABC123'], '/products/ABC123/edit');

        $response = $this->redirectHandler->redirectToOperation($data, $operation, $request, 'update');

        $this->assertSame('/products/ABC123/edit', $response->getTargetUrl());
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testItRedirectsToOperationForDeleteWithoutArgumentsByDefault(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Delete(name: 'app_dummy_delete'))
            ->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $request = $this->createMock(Request::class);

        $this->operationRouteNameFactory
            ->expects($this->once())
            ->method('createRouteName')
            ->with($operation, 'index')
            ->willReturn('app_dummy_index');

        $this->mockFilterStorage();
        $this->mockRouter('app_dummy_index', [], '/dummies');

        $response = $this->redirectHandler->redirectToOperation($data, $operation, $request, 'index');

        $this->assertSame('/dummies', $response->getTargetUrl());
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testItRedirectsToOperationWithCustomRedirectArguments(): void
    {
        $data = new \stdClass();
        $data->id = 'xyz';
        $data->slug = 'my-book';

        $operation = new Create(
            name: 'app_book_create',
            redirectArguments: ['slug' => 'resource.slug'],
        );
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $operation = $operation->withResource($resource);

        $request = $this->createMock(Request::class);

        $this->operationRouteNameFactory
            ->expects($this->once())
            ->method('createRouteName')
            ->with($operation, 'show')
            ->willReturn('app_book_show');

        $this->filterStorage->method('all')->willReturn([]);
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_book_show', ['slug' => 'my-book'])
            ->willReturn('/books/my-book');

        $response = $this->redirectHandler->redirectToOperation($data, $operation, $request, 'show');

        $this->assertSame('/books/my-book', $response->getTargetUrl());
    }

    public function testItThrowsExceptionWhenOperationHasNoResourceInRedirectToOperation(): void
    {
        $data = new \stdClass();
        $operation = new Create(name: 'app_dummy_create');
        $request = $this->createMock(Request::class);

        $this->operationRouteNameFactory
            ->method('createRouteName')
            ->willReturn('app_dummy_show');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_create" has no resource, but it should.');

        $this->redirectHandler->redirectToOperation($data, $operation, $request, 'show');
    }

    public function testItParsesNestedArrayArguments(): void
    {
        $data = $this->createDataWithId();
        $data->code = 'ABC';

        $operation = (new Create(
            redirectToRoute: 'app_dummy_index',
            redirectArguments: [
                'id' => 'resource.id',
                'criteria' => [
                    'code' => 'resource.code',
                    'enabled' => true,
                ],
            ],
        ))->withResource(new ResourceMetadata(alias: 'app.book'));
        $request = $this->createMock(Request::class);

        $this->mockFilterStorage();
        $this->mockRouter('app_dummy_index', [
            'id' => 'xyz',
            'criteria' => [
                'code' => 'ABC',
                'enabled' => true,
            ],
        ], '/dummies');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItParsesResourcePropertyPathWithPropertyAccessor(): void
    {
        $data = $this->createDataWithId();
        $data->author = new \stdClass();
        $data->author->name = 'John Doe';

        $operation = (new Create(
            redirectToRoute: 'app_book_show',
            redirectArguments: ['id' => 'resource.id', 'author' => 'resource.author.name'],
        ))->withResource(new ResourceMetadata(alias: 'app.book'));
        $request = $this->createMock(Request::class);

        $this->mockFilterStorage();
        $this->mockRouter('app_book_show', ['id' => 'xyz', 'author' => 'John Doe'], '/books/xyz');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItUsesExpressionParserForStringValuesWithoutResourcePrefix(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Create(
            redirectToRoute: 'app_book_show',
            redirectArguments: ['id' => 'resource.id', 'page' => '1'],
        ))->withResource(new ResourceMetadata(alias: 'app.book')); // name is null
        $request = $this->createMock(Request::class);

        $this->argumentParser
            ->expects($this->once())
            ->method('parseExpression')
            ->with('1', ['resource' => $data]) // Only 'resource', no name
            ->willReturn('1');

        $this->mockFilterStorage();
        $this->router
            ->method('generate')
            ->with('app_book_show', ['id' => 'xyz', 'page' => '1'])
            ->willReturn('/books/xyz?page=1');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItSkipsExpressionParserForNonStringScalarValues(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Create(
            redirectToRoute: 'app_book_show',
            redirectArguments: ['id' => 'resource.id', 'page' => 1, 'active' => true],
        ))->withResource(new ResourceMetadata(alias: 'app.book'));
        $request = $this->createMock(Request::class);

        // parseExpression should not be called for non-string values
        $this->argumentParser
            ->expects($this->never())
            ->method('parseExpression');

        $this->mockFilterStorage();
        $this->mockRouter('app_book_show', ['id' => 'xyz', 'page' => 1, 'active' => true], '/books/xyz');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItUsesExpressionParserWhenPropertyAccessorCannotReadProperty(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Create(
            redirectToRoute: 'app_book_show',
            redirectArguments: ['id' => 'resource.id', 'nonExistent' => 'resource.nonExistentProperty'],
        ))->withResource(new ResourceMetadata(alias: 'app.book', name: 'book'));
        $request = $this->createMock(Request::class);

        // When property is not readable, should fall back to expression parser
        $this->argumentParser
            ->expects($this->once())
            ->method('parseExpression')
            ->with('resource.nonExistentProperty', ['resource' => $data, 'book' => $data])
            ->willReturn('default_value');

        $this->mockFilterStorage();
        $this->mockRouter('app_book_show', ['id' => 'xyz', 'nonExistent' => 'default_value'], '/books/xyz');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItAddsResourceNameToVariablesWhenResourceNameIsSet(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Create(
            redirectToRoute: 'app_book_show',
            redirectArguments: ['id' => 'resource.id', 'type' => 'hardcover'],
        ))->withResource(new ResourceMetadata(alias: 'app.book', name: 'book'));
        $request = $this->createMock(Request::class);

        // Should include both 'resource' and resource name ('book') in variables
        $this->argumentParser
            ->expects($this->once())
            ->method('parseExpression')
            ->with('hardcover', ['resource' => $data, 'book' => $data])
            ->willReturn('hardcover');

        $this->mockFilterStorage();
        $this->router
            ->method('generate')
            ->willReturn('/books/xyz');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItDoesNotAddResourceNameToVariablesWhenResourceNameIsNull(): void
    {
        $data = $this->createDataWithId();
        $operation = (new Create(
            redirectToRoute: 'app_book_show',
            redirectArguments: ['id' => 'resource.id', 'type' => 'hardcover'],
        ))->withResource(new ResourceMetadata(alias: 'app.book')); // name is null
        $request = $this->createMock(Request::class);

        // Should include only 'resource' in variables (no resource name)
        $this->argumentParser
            ->expects($this->once())
            ->method('parseExpression')
            ->with('hardcover', ['resource' => $data])
            ->willReturn('hardcover');

        $this->mockFilterStorage();
        $this->router
            ->method('generate')
            ->willReturn('/books/xyz');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }

    public function testItHandlesArrayDataWithResourcePrefix(): void
    {
        $data = ['id' => 'xyz', 'title' => 'My Book'];
        $operation = (new Create(
            redirectToRoute: 'app_book_show',
            redirectArguments: ['id' => 'resource.id', 'title' => 'resource.title'],
        ))->withResource(new ResourceMetadata(alias: 'app.book'));
        $request = $this->createMock(Request::class);

        // When data is not an object, property accessor cannot read it
        // Should fall back to expression parser
        $this->argumentParser
            ->expects($this->exactly(2))
            ->method('parseExpression')
            ->willReturnCallback(function ($value, $variables) use ($data) {
                if ($value === 'resource.id') {
                    return 'xyz';
                }
                if ($value === 'resource.title') {
                    return 'My Book';
                }

                return $value;
            });

        $this->mockFilterStorage();
        $this->mockRouter('app_book_show', ['id' => 'xyz', 'title' => 'My Book'], '/books/xyz');

        $this->redirectHandler->redirectToResource($data, $operation, $request);
    }
}
