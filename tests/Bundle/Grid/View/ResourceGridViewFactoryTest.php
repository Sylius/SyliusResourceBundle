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
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactory;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface;
use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\Request;

final class ResourceGridViewFactoryTest extends TestCase
{
    private const DRIVER_CONFIG_BEFORE = [
        'repository' => [
            'method' => 'createByCustomerQueryBuilder',
            'arguments' => ['$customerId'],
        ],
    ];

    private const DRIVER_CONFIG_AFTER = [
        'repository' => [
            'method' => 'createByCustomerQueryBuilder',
            'arguments' => [5],
        ],
    ];

    private const GRID_DATA = ['foo', 'bar'];

    /** @var DataProviderInterface&MockObject */
    private DataProviderInterface $dataProvider;

    /** @var ParametersParserInterface&MockObject */
    private ParametersParserInterface $parametersParser;

    private ResourceGridViewFactory $factory;

    protected function setUp(): void
    {
        $this->dataProvider = $this->createMock(DataProviderInterface::class);
        $this->parametersParser = $this->createMock(ParametersParserInterface::class);
        $this->factory = new ResourceGridViewFactory($this->dataProvider, $this->parametersParser);
    }

    public function testItImplementsResourceGridViewFactoryInterface(): void
    {
        $this->assertInstanceOf(ResourceGridViewFactoryInterface::class, $this->factory);
    }

    public function testItUsesDataProviderToCreateAViewWithDataAndDefinition(): void
    {
        $parameters = new Parameters();
        $resourceMetadata = $this->createMock(MetadataInterface::class);
        $request = $this->createMock(Request::class);
        $requestConfiguration = $this->createConfiguredMock(RequestConfiguration::class, [
            'getRequest' => $request,
        ]);
        $grid = $this->createGridMock();

        $this->configureParametersParser($request);
        $this->configureDataProvider($grid, $parameters);

        $result = $this->factory->create($grid, $parameters, $resourceMetadata, $requestConfiguration);

        $this->assertResourceGridView($result, $grid, $parameters, $resourceMetadata, $requestConfiguration);
    }

    private function createGridMock(): Grid&MockObject
    {
        return $this->createConfiguredMock(Grid::class, [
            'getDriverConfiguration' => self::DRIVER_CONFIG_BEFORE,
        ]);
    }

    private function configureParametersParser(Request $request): void
    {
        $this->parametersParser->expects($this->once())
            ->method('parseRequestValues')
            ->with(self::DRIVER_CONFIG_BEFORE, $request)
            ->willReturn(self::DRIVER_CONFIG_AFTER);
    }

    private function configureDataProvider(Grid&MockObject $grid, Parameters $parameters): void
    {
        /** @var MockObject $gridMock */
        $gridMock = $grid;
        $gridMock->expects($this->once())
            ->method('setDriverConfiguration')
            ->with(self::DRIVER_CONFIG_AFTER);

        $this->dataProvider->expects($this->once())
            ->method('getData')
            ->with($grid, $parameters)
            ->willReturn(self::GRID_DATA);
    }

    private function assertResourceGridView(
        ResourceGridView $result,
        Grid $grid,
        Parameters $parameters,
        MetadataInterface $resourceMetadata,
        RequestConfiguration $requestConfiguration,
    ): void {
        $this->assertInstanceOf(ResourceGridView::class, $result);
        $this->assertSame(self::GRID_DATA, $result->getData());
        $this->assertSame($grid, $result->getDefinition());
        $this->assertSame($parameters, $result->getParameters());
        $this->assertSame($resourceMetadata, $result->getMetadata());
        $this->assertSame($requestConfiguration, $result->getRequestConfiguration());
    }
}
