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

namespace Sylius\Bundle\ResourceBundle\Tests\Grid\View;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\View\GridContextAwareInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\Request;

final class ResourceGridViewTest extends TestCase
{
    private const GRID_DATA = ['foo', 'bar'];

    private ResourceGridView $gridView;

    private MetadataInterface $resourceMetadata;

    private RequestConfiguration $requestConfiguration;

    protected function setUp(): void
    {
        $this->resourceMetadata = $this->createMock(MetadataInterface::class);
        $this->requestConfiguration = $this->createMock(RequestConfiguration::class);
        $this->gridView = $this->createResourceGridView();
    }

    public function testItExtendsDefaultGridView(): void
    {
        $this->assertInstanceOf(GridView::class, $this->gridView);
    }

    public function testItHasResourceMetadata(): void
    {
        $this->assertSame($this->resourceMetadata, $this->gridView->getMetadata());
    }

    public function testItHasRequestConfiguration(): void
    {
        $this->assertSame($this->requestConfiguration, $this->gridView->getRequestConfiguration());
    }

    public function testItProvidesAGridContextFromTheRequestConfiguration(): void
    {
        $request = new Request(
            query: ['page' => 2, 'productId' => 'query'],
            request: ['foo' => 'bar'],
            attributes: ['productId' => 42],
        );
        $this->requestConfiguration->method('getRequest')->willReturn($request);
        $this->requestConfiguration->method('getSection')->willReturn('admin');

        $gridContext = $this->gridView->getGridContext();

        $this->assertInstanceOf(GridContextAwareInterface::class, $this->gridView);
        $this->assertSame($gridContext, $this->gridView->getGridContext());
        $this->assertSame($this->resourceMetadata, $gridContext->getMetadata());
        $this->assertSame('admin', $gridContext->getSection());
        $this->assertSame(['foo' => 'bar', 'page' => 2, 'productId' => 42], $gridContext->getVariables());
        $this->assertSame($this->requestConfiguration, $gridContext->getContext()->get(RequestConfigurationOption::class)?->requestConfiguration());
        $this->assertSame($request, $gridContext->getContext()->get(RequestOption::class)?->request());
    }

    private function createResourceGridView(): ResourceGridView
    {
        return new ResourceGridView(
            self::GRID_DATA,
            $this->createMock(Grid::class),
            new Parameters(),
            $this->resourceMetadata,
            $this->requestConfiguration,
        );
    }
}
