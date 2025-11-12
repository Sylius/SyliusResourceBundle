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
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Metadata\MetadataInterface;

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
