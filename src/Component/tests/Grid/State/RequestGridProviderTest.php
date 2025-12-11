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

namespace Sylius\Resource\Tests\Grid\State;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\State\RequestGridProvider;
use Sylius\Resource\Grid\View\Factory\GridViewFactoryInterface;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

final class RequestGridProviderTest extends TestCase
{
    private RequestGridProvider $provider;

    private GridViewFactoryInterface|MockObject $gridViewFactory;

    private GridProviderInterface|MockObject $gridProvider;

    protected function setUp(): void
    {
        $this->gridViewFactory = $this->createMock(GridViewFactoryInterface::class);
        $this->gridProvider = $this->createMock(GridProviderInterface::class);
        $this->provider = new RequestGridProvider(
            $this->gridViewFactory,
            $this->gridProvider,
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RequestGridProvider::class, $this->provider);
    }

    public function testItProvidesAGridView(): void
    {
        $request = $this->createMock(Request::class);
        $context = new Context(new RequestOption($request));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag();
        $gridDefinition = $this->createMock(Grid::class);
        $gridView = $this->createMock(GridView::class);

        $this->gridProvider->method('get')->with('app_book')->willReturn($gridDefinition);
        $gridDefinition->method('getDriverConfiguration')->willReturn([]);
        $this->gridViewFactory->method('create')->with($gridDefinition, $context, new Parameters(), [])->willReturn($gridView);

        $this->assertSame($gridView, $this->provider->provide($operation, $context));
    }

    public function testItSetsCurrentPageFromRequest(): void
    {
        $request = $this->createMock(Request::class);
        $context = new Context(new RequestOption($request));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['page' => 42]);
        $gridDefinition = $this->createMock(Grid::class);
        $gridView = $this->createMock(GridView::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $this->gridProvider->method('get')->with('app_book')->willReturn($gridDefinition);
        $gridDefinition->method('getLimits')->willReturn([]);
        $gridDefinition->method('getDriverConfiguration')->willReturn([]);
        $this->gridViewFactory->method('create')->with($gridDefinition, $context, new Parameters(['page' => 42]), [])->willReturn($gridView);

        $gridView->method('getData')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(42)->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setMaxPerPage')->with(10)->willReturn($pagerfanta);

        $this->assertSame($gridView, $this->provider->provide($operation, $context));
    }

    public function testItSetsMaxPerPageFromRequest(): void
    {
        $request = $this->createMock(Request::class);
        $context = new Context(new RequestOption($request));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['limit' => 25]);
        $gridDefinition = $this->createMock(Grid::class);
        $gridView = $this->createMock(GridView::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $this->gridProvider->method('get')->with('app_book')->willReturn($gridDefinition);
        $gridDefinition->method('getDriverConfiguration')->willReturn([]);
        $gridDefinition->method('getLimits')->willReturn([10, 25]);
        $this->gridViewFactory->method('create')->with($gridDefinition, $context, new Parameters(['limit' => 25]), [])->willReturn($gridView);

        $gridView->method('getData')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(1)->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setMaxPerPage')->with(25)->willReturn($pagerfanta);

        $this->assertSame($gridView, $this->provider->provide($operation, $context));
    }

    public function testItSetsMaxPerPageFromGridConfiguration(): void
    {
        $request = $this->createMock(Request::class);
        $context = new Context(new RequestOption($request));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag();
        $gridDefinition = $this->createMock(Grid::class);
        $gridView = $this->createMock(GridView::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $this->gridProvider->method('get')->with('app_book')->willReturn($gridDefinition);
        $gridDefinition->method('getDriverConfiguration')->willReturn([]);
        $gridDefinition->method('getLimits')->willReturn([15, 30]);
        $this->gridViewFactory->method('create')->with($gridDefinition, $context, new Parameters([]), [])->willReturn($gridView);

        $gridView->method('getData')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(1)->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setMaxPerPage')->with(15)->willReturn($pagerfanta);

        $this->assertSame($gridView, $this->provider->provide($operation, $context));
    }

    public function testItLimitsMaxPerPageWithMaxGridConfigurationLimit(): void
    {
        $request = $this->createMock(Request::class);
        $context = new Context(new RequestOption($request));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['limit' => 40]);
        $gridDefinition = $this->createMock(Grid::class);
        $gridView = $this->createMock(GridView::class);
        $pagerfanta = $this->createMock(Pagerfanta::class);

        $this->gridProvider->method('get')->with('app_book')->willReturn($gridDefinition);
        $gridDefinition->method('getDriverConfiguration')->willReturn([]);
        $gridDefinition->method('getLimits')->willReturn([15, 30]);
        $this->gridViewFactory->method('create')->with($gridDefinition, $context, new Parameters(['limit' => 40]), [])->willReturn($gridView);

        $gridView->method('getData')->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setCurrentPage')->with(1)->willReturn($pagerfanta);
        $pagerfanta->expects($this->once())->method('setMaxPerPage')->with(30)->willReturn($pagerfanta);

        $this->assertSame($gridView, $this->provider->provide($operation, $context));
    }

    public function testItThrowsAnExceptionWhenOperationHasNoGrid(): void
    {
        $request = $this->createMock(Request::class);
        $operation = new Index(name: 'app_book');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation has no grid, so you cannot use this provider for operation "app_book"');

        $this->provider->provide($operation, new Context(new RequestOption($request)));
    }

    public function testItThrowsAnExceptionWhenOperationDoesNotImplementTheGridAwareInterface(): void
    {
        $request = $this->createMock(Request::class);
        $operation = new Create(name: 'app_book');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use a grid if your operation does not implement "Sylius\Resource\Metadata\GridAwareOperationInterface".');

        $this->provider->provide($operation, new Context(new RequestOption($request)));
    }
}
