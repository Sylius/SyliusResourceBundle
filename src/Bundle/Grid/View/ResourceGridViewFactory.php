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

namespace Sylius\Bundle\ResourceBundle\Grid\View;

use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\Parser\GridExpressionParser;
use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Metadata\MetadataInterface;

final class ResourceGridViewFactory implements ResourceGridViewFactoryInterface
{
    private DataProviderInterface $dataProvider;

    private ParametersParserInterface $parametersParser;

    private ?GridExpressionParser $expressionParser;

    public function __construct(
        DataProviderInterface $dataProvider,
        ParametersParserInterface $parametersParser,
        ?GridExpressionParser $expressionParser = null,
    ) {
        $this->dataProvider = $dataProvider;
        $this->parametersParser = $parametersParser;
        $this->expressionParser = $expressionParser;
    }

    public function create(
        Grid $grid,
        Parameters $parameters,
        MetadataInterface $metadata,
        RequestConfiguration $requestConfiguration,
    ): ResourceGridView {
        $driverConfiguration = $grid->getDriverConfiguration();
        $request = $requestConfiguration->getRequest();

        $driverConfiguration = $this->parametersParser->parseRequestValues($driverConfiguration, $request);

        if (null !== $this->expressionParser) {
            // Same variables as ResourceGridView::getVariables()
            $variables = array_merge($request->request->all(), $request->query->all(), $request->attributes->all());
            $driverConfiguration = $this->expressionParser->parseExpressions($driverConfiguration, $variables);
        }

        $grid->setDriverConfiguration($driverConfiguration);

        return new ResourceGridView($this->dataProvider->getData($grid, $parameters), $grid, $parameters, $metadata, $requestConfiguration);
    }
}
