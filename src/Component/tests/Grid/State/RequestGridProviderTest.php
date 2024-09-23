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
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
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
    use ProphecyTrait;

    private RequestGridProvider $provider;

    private GridViewFactoryInterface|ObjectProphecy $gridViewFactory;

    private GridProviderInterface|ObjectProphecy $gridProvider;

    protected function setUp(): void
    {
        $this->gridViewFactory = $this->prophesize(GridViewFactoryInterface::class);
        $this->gridProvider = $this->prophesize(GridProviderInterface::class);
        $this->provider = new RequestGridProvider(
            $this->gridViewFactory->reveal(),
            $this->gridProvider->reveal(),
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RequestGridProvider::class, $this->provider);
    }

    public function testItProvidesAGridView(): void
    {
        $request = $this->prophesize(Request::class);
        $context = new Context(new RequestOption($request->reveal()));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag();
        $gridDefinition = $this->prophesize(Grid::class);
        $gridView = $this->prophesize(GridView::class);

        $this->gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);
        $this->gridViewFactory->create($gridDefinition, $context, new Parameters(), [])->willReturn($gridView);

        $this->assertSame($gridView->reveal(), $this->provider->provide($operation, $context));
    }

    public function testItSetsCurrentPageFromRequest(): void
    {
        $request = $this->prophesize(Request::class);
        $context = new Context(new RequestOption($request->reveal()));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['page' => 42]);
        $gridDefinition = $this->prophesize(Grid::class);
        $gridView = $this->prophesize(GridView::class);
        $pagerfanta = $this->prophesize(Pagerfanta::class);

        $this->gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getLimits()->willReturn([]);
        $gridDefinition->getDriverConfiguration()->willReturn([]);
        $this->gridViewFactory->create($gridDefinition, $context, new Parameters(['page' => 42]), [])->willReturn($gridView);

        $gridView->getData()->willReturn($pagerfanta);
        $pagerfanta->setCurrentPage(42)->willReturn($pagerfanta)->shouldBeCalled();
        $pagerfanta->setMaxPerPage(10)->willReturn($pagerfanta)->shouldBeCalled();

        $this->assertSame($gridView->reveal(), $this->provider->provide($operation, $context));
    }

    public function testItSetsMaxPerPageFromRequest(): void
    {
        $request = $this->prophesize(Request::class);
        $context = new Context(new RequestOption($request->reveal()));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['limit' => 25]);
        $gridDefinition = $this->prophesize(Grid::class);
        $gridView = $this->prophesize(GridView::class);
        $pagerfanta = $this->prophesize(Pagerfanta::class);

        $this->gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);
        $gridDefinition->getLimits()->willReturn([10, 25]);
        $this->gridViewFactory->create($gridDefinition, $context, new Parameters(['limit' => 25]), [])->willReturn($gridView);

        $gridView->getData()->willReturn($pagerfanta);
        $pagerfanta->setCurrentPage(1)->willReturn($pagerfanta)->shouldBeCalled();
        $pagerfanta->setMaxPerPage(25)->willReturn($pagerfanta)->shouldBeCalled();

        $this->assertSame($gridView->reveal(), $this->provider->provide($operation, $context));
    }

    public function testItSetsMaxPerPageFromGridConfiguration(): void
    {
        $request = $this->prophesize(Request::class);
        $context = new Context(new RequestOption($request->reveal()));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag();
        $gridDefinition = $this->prophesize(Grid::class);
        $gridView = $this->prophesize(GridView::class);
        $pagerfanta = $this->prophesize(Pagerfanta::class);

        $this->gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);
        $gridDefinition->getLimits()->willReturn([15, 30]);
        $this->gridViewFactory->create($gridDefinition, $context, new Parameters([]), [])->willReturn($gridView);

        $gridView->getData()->willReturn($pagerfanta);
        $pagerfanta->setCurrentPage(1)->willReturn($pagerfanta)->shouldBeCalled();
        $pagerfanta->setMaxPerPage(15)->willReturn($pagerfanta)->shouldBeCalled();

        $this->assertSame($gridView->reveal(), $this->provider->provide($operation, $context));
    }

    public function testItLimitsMaxPerPageWithMaxGridConfigurationLimit(): void
    {
        $request = $this->prophesize(Request::class);
        $context = new Context(new RequestOption($request->reveal()));
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['limit' => 40]);
        $gridDefinition = $this->prophesize(Grid::class);
        $gridView = $this->prophesize(GridView::class);
        $pagerfanta = $this->prophesize(Pagerfanta::class);

        $this->gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);
        $gridDefinition->getLimits()->willReturn([15, 30]);
        $this->gridViewFactory->create($gridDefinition, $context, new Parameters(['limit' => 40]), [])->willReturn($gridView);

        $gridView->getData()->willReturn($pagerfanta);
        $pagerfanta->setCurrentPage(1)->willReturn($pagerfanta)->shouldBeCalled();
        $pagerfanta->setMaxPerPage(30)->willReturn($pagerfanta)->shouldBeCalled();

        $this->assertSame($gridView->reveal(), $this->provider->provide($operation, $context));
    }

    public function testItThrowsAnExceptionWhenOperationHasNoGrid(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = new Index(name: 'app_book');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation has no grid, so you cannot use this provider for operation "app_book"');

        $this->provider->provide($operation, new Context(new RequestOption($request->reveal())));
    }

    public function testItThrowsAnExceptionWhenOperationDoesNotImplementTheGridAwareInterface(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = new Create(name: 'app_book');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use a grid if your operation does not implement "Sylius\Resource\Metadata\GridAwareOperationInterface".');

        $this->provider->provide($operation, new Context(new RequestOption($request->reveal())));
    }
}
