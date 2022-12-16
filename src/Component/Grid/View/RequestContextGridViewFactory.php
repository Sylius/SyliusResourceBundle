<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Grid\View;

use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Symfony\HttpFoundation\Request\Parser\ParametersParserInterface;

final class RequestContextGridViewFactory implements ContextGridViewFactoryInterface
{
    private DataProviderInterface $dataProvider;

    private ParametersParserInterface $parametersParser;

    public function __construct(DataProviderInterface $dataProvider, ParametersParserInterface $parametersParser)
    {
        $this->dataProvider = $dataProvider;
        $this->parametersParser = $parametersParser;
    }

    public function create(Grid $grid, Parameters $parameters, Context $context): ?ContextGridView
    {
        $request = $context->get(RequestOption::class)->request();

        if (null == $request) {
            return null;
        }

        $driverConfiguration = $grid->getDriverConfiguration();

        $grid->setDriverConfiguration($this->parametersParser->parseRequestValues($driverConfiguration, $request));

        return new ContextGridView($this->dataProvider->getData($grid, $parameters), $grid, $parameters, $context);
    }
}
