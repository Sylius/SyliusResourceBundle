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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\View\LegacyGridViewFactory;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Grid\View\Factory\GridViewFactoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;

final class LegacyGridViewFactoryTest extends TestCase
{
    /** @var ResourceGridViewFactoryInterface&MockObject */
    private ResourceGridViewFactoryInterface $resourceGridViewFactory;

    /** @var GridViewFactoryInterface&MockObject */
    private GridViewFactoryInterface $decorated;

    private LegacyGridViewFactory $factory;

    protected function setUp(): void
    {
        $this->resourceGridViewFactory = $this->createMock(ResourceGridViewFactoryInterface::class);
        $this->decorated = $this->createMock(GridViewFactoryInterface::class);
        $this->factory = new LegacyGridViewFactory($this->resourceGridViewFactory, $this->decorated);
    }

    public function testItCreatesALegacyResourceGridView(): void
    {
        $grid = $this->createMock(Grid::class);
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $metadata = $this->createMock(MetadataInterface::class);
        $resourceGridView = $this->createMock(ResourceGridView::class);
        $parameters = new Parameters();

        $context = $this->createContextWithRequestConfiguration($requestConfiguration, $metadata);

        $this->configureResourceGridViewFactory($grid, $parameters, $metadata, $requestConfiguration, $resourceGridView);

        $result = $this->factory->create($grid, $context, $parameters, []);

        $this->assertSame($resourceGridView, $result);
    }

    public function testItCreatesAGridViewWhenContextHasNoRequestConfiguration(): void
    {
        $grid = $this->createMock(Grid::class);
        $metadata = $this->createMock(MetadataInterface::class);
        $gridView = $this->createMock(GridView::class);
        $parameters = new Parameters();

        $context = $this->createContextWithoutRequestConfiguration($metadata);

        $this->configureDecoratedFactory($grid, $context, $parameters, $gridView);

        $result = $this->factory->create($grid, $context, $parameters, []);

        $this->assertSame($gridView, $result);
    }

    private function createContextWithRequestConfiguration(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
    ): Context {
        return new Context(
            new RequestConfigurationOption($requestConfiguration),
            new MetadataOption($metadata),
        );
    }

    private function createContextWithoutRequestConfiguration(MetadataInterface $metadata): Context
    {
        return new Context(
            new MetadataOption($metadata),
        );
    }

    private function configureResourceGridViewFactory(
        Grid $grid,
        Parameters $parameters,
        MetadataInterface $metadata,
        RequestConfiguration $requestConfiguration,
        ResourceGridView $resourceGridView,
    ): void {
        $this->resourceGridViewFactory->expects($this->once())
            ->method('create')
            ->with($grid, $parameters, $metadata, $requestConfiguration)
            ->willReturn($resourceGridView);
    }

    private function configureDecoratedFactory(
        Grid $grid,
        Context $context,
        Parameters $parameters,
        GridView $gridView,
    ): void {
        $this->decorated->expects($this->once())
            ->method('create')
            ->with($grid, $context, $parameters, [])
            ->willReturn($gridView);
    }
}
