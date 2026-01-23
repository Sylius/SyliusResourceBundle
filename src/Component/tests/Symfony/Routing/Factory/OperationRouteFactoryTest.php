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

namespace Sylius\Resource\Tests\Symfony\Routing\Factory;

use Behat\Transliterator\Transliterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Inflector\Inflector;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation\DashPathSegmentNameGenerator;
use Sylius\Resource\Metadata\Operation\UnderscorePathSegmentNameGenerator;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;
use Symfony\Component\Routing\Route;

#[CoversClass(OperationRouteFactory::class)]
final class OperationRouteFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private OperationRouteFactory $operationRouteFactory;

    private OperationRouteFactory $bcOperationRouteFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->operationRouteFactory = new OperationRouteFactory($this->routePathFactory, new DashPathSegmentNameGenerator(new Inflector()), false);
        $this->bcOperationRouteFactory = new OperationRouteFactory($this->routePathFactory, new DashPathSegmentNameGenerator(new Inflector()), true);
    }

    public function testItCreatesRouteWithDefaultPathWithBcLayer(): void
    {
        $this->markAsSkippedIfBcLayerCannotBeEnabled();

        $metadata = $this->createMock(MetadataInterface::class);
        $metadata->method('getPluralName')->willReturn('books');

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'books')
            ->willReturn('/books');

        $route = $this->bcOperationRouteFactory->create($metadata, $resource, $operation);

        $this->assertInstanceOf(Route::class, $route);
        $this->assertSame('/books', $route->getPath());
        $this->assertSame('sylius.main_controller', $route->getDefault('_controller'));
        $this->assertSame(['resource' => 'app.book'], $route->getDefault('_sylius'));
    }

    public function testItCreatesRouteWithDefaultPath(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book', pluralName: 'books');
        $operation = new Index();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'books')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertInstanceOf(Route::class, $route);
        $this->assertSame('/books', $route->getPath());
        $this->assertSame('sylius.main_controller', $route->getDefault('_controller'));
        $this->assertSame(['resource' => 'app.book'], $route->getDefault('_sylius'));
    }

    public function testItCreatesRouteWithCustomPath(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index(path: '/custom/books/list');

        $this->routePathFactory
            ->expects($this->never())
            ->method('createRoutePath');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/custom/books/list', $route->getPath());
    }

    public function testItCreatesRouteWithRoutePrefixWithBcLayer(): void
    {
        $this->markAsSkippedIfBcLayerCannotBeEnabled();

        $metadata = $this->createMock(MetadataInterface::class);
        $metadata->method('getPluralName')->willReturn('books');

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = (new Index())->withRoutePrefix('/admin');

        $this->routePathFactory
            ->method('createRoutePath')
            ->with($operation, 'books')
            ->willReturn('/books');

        $route = $this->bcOperationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/admin/books', $route->getPath());
    }

    public function testItCreatesRouteWithRoutePrefix(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book', pluralName: 'books');
        $operation = (new Index())->withRoutePrefix('/admin');

        $this->routePathFactory
            ->method('createRoutePath')
            ->with($operation, 'books')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/admin/books', $route->getPath());
    }

    public function testItCreatesRouteWithCustomPathAndRoutePrefix(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index(path: '/custom/books/list', routePrefix: '/admin');

        $this->routePathFactory
            ->expects($this->never())
            ->method('createRoutePath');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/admin/custom/books/list', $route->getPath());
    }

    public function testItCreatesRouteWithCustomPathAndRoutePrefixAndTooManySlashes(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index(path: '/custom/books/list', routePrefix: '/admin/');

        $this->routePathFactory
            ->expects($this->never())
            ->method('createRoutePath');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/admin/custom/books/list', $route->getPath());
    }

    public function testItCreatesRouteWithSection(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book', section: 'admin');
        $operation = new Index();

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $syliusOptions = $route->getDefault('_sylius');
        $this->assertSame('app.book', $syliusOptions['resource']);
        $this->assertSame('admin', $syliusOptions['section']);
    }

    public function testItCreatesRouteWithVars(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = (new Index())->withVars(['grid' => 'app_book']);

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $syliusOptions = $route->getDefault('_sylius');
        $this->assertSame('app.book', $syliusOptions['resource']);
        $this->assertSame(['grid' => 'app_book'], $syliusOptions['vars']);
    }

    public function testItCreatesRouteWithoutVarsWhenNull(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index();

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $syliusOptions = $route->getDefault('_sylius');
        $this->assertSame('app.book', $syliusOptions['resource']);
        $this->assertArrayNotHasKey('vars', $syliusOptions);
    }

    public function testItCreatesRouteWithRequirements(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = (new Show())->withRouteRequirements(['id' => '\d+']);

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books/{id}');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame(['id' => '\d+'], $route->getRequirements());
    }

    public function testItCreatesRouteWithEmptyRequirementsWhenNull(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index();

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame([], $route->getRequirements());
    }

    public function testItCreatesRouteWithMethods(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = (new Index())->withMethods(['GET', 'POST']);

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame(['GET', 'POST'], $route->getMethods());
    }

    public function testItCreatesRouteWithDefaultMethodsForIndex(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata->method('getPluralName')->willReturn('books');

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index(); // Index has default method 'GET'

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame(['GET'], $route->getMethods());
    }

    public function testItCreatesRouteWithCondition(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata->method('getPluralName')->willReturn('books');

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = (new Index())->withRouteCondition('context.getMethod() == "GET"');

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('context.getMethod() == "GET"', $route->getCondition());
    }

    public function testItCreatesRouteWithEmptyConditionWhenNotSet(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book');
        $operation = new Index();

        $this->routePathFactory
            ->method('createRoutePath')
            ->willReturn('/books');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('', $route->getCondition());
    }

    public function testItUrlizesPluralName(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book_category', pluralName: 'Book Categories');
        $operation = new Index();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'book-categories')
            ->willReturn('/book-categories');

        $route = $this->operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/book-categories', $route->getPath());
    }

    public function testItUrlizesPluralNameWithBcLayerEnabled(): void
    {
        $this->markAsSkippedIfBcLayerCannotBeEnabled();

        $metadata = $this->createMock(MetadataInterface::class);
        $metadata->method('getPluralName')->willReturn('Book Categories');

        $resource = new ResourceMetadata(alias: 'app.book_category');
        $operation = new Index();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'book-categories')
            ->willReturn('/book-categories');

        $route = $this->bcOperationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/book-categories', $route->getPath());
    }

    public function testItUrlizesPluralNameUsingUnderscoreAsSeparator(): void
    {
        $operationRouteFactory = new OperationRouteFactory($this->routePathFactory, new UnderscorePathSegmentNameGenerator(new Inflector()), false);

        $metadata = $this->createMock(MetadataInterface::class);

        $resource = new ResourceMetadata(alias: 'app.book_category', pluralName: 'BookCategories');
        $operation = new Index();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'book_categories')
            ->willReturn('/book_categories');

        $route = $operationRouteFactory->create($metadata, $resource, $operation);

        $this->assertSame('/book_categories', $route->getPath());
    }

    private function markAsSkippedIfBcLayerCannotBeEnabled(): void
    {
        if (!class_exists(Transliterator::class)) {
            $this->markTestSkipped('This test requires The Behat Transliterator.');
        }
    }
}
