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

namespace Sylius\Resource\Tests\Grid\View\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Context\Option\OperationOption;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\Factory\GridViewFactory;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Metadata;
use Symfony\Component\HttpFoundation\Request;

final class GridViewFactoryTest extends TestCase
{
    public function testItCreatesAGridViewWithItsContext(): void
    {
        $grid = Grid::fromCodeAndDriverConfiguration('app_book', 'doctrine/orm', []);
        $parameters = new Parameters(['page' => 2]);
        $metadata = Metadata::fromAliasAndConfiguration('app.book', ['driver' => 'doctrine/orm']);
        $operation = new Index(grid: 'app_book');
        $request = new Request(
            query: ['page' => 2, 'author' => 'query'],
            attributes: ['_route' => 'app_admin_book_index', '_route_params' => ['author' => 'tolkien'], '_controller' => 'foo'],
        );
        $context = new Context(new RequestOption($request), new MetadataOption($metadata), new OperationOption($operation));

        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->method('getData')->with($grid, $parameters)->willReturn(['data']);

        $gridView = (new GridViewFactory($dataProvider))->create($grid, $context, $parameters, []);

        $this->assertInstanceOf(ContextualGridView::class, $gridView);
        $this->assertSame(['data'], $gridView->getData());
        $this->assertSame($grid, $gridView->getDefinition());
        $this->assertSame($parameters, $gridView->getParameters());

        $gridContext = $gridView->getGridContext();
        $this->assertSame($metadata, $gridContext->getMetadata());
        $this->assertSame($operation, $gridContext->getOperation());
        $this->assertSame($context, $gridContext->getContext());
        // Route parameters take precedence over the query ones, as in the legacy controller
        $this->assertSame(['page' => 2, 'author' => 'tolkien'], $gridContext->getVariables());
    }

    public function testItCreatesAGridViewWithoutRequest(): void
    {
        $grid = Grid::fromCodeAndDriverConfiguration('app_book', 'doctrine/orm', []);

        $gridView = (new GridViewFactory($this->createMock(DataProviderInterface::class)))->create($grid, new Context(), new Parameters(), []);

        $this->assertInstanceOf(ContextualGridView::class, $gridView);
        $this->assertSame([], $gridView->getGridContext()->getVariables());
    }
}
