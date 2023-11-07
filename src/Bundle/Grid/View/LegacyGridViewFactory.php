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

use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Grid\View\Factory\GridViewFactoryInterface;

final class LegacyGridViewFactory implements GridViewFactoryInterface
{
    public function __construct(
        private ResourceGridViewFactoryInterface $resourceGridViewFactory,
        private GridViewFactoryInterface $decorated,
    ) {
    }

    public function create(
        Grid $grid,
        Context $context,
        Parameters $parameters,
        array $driverConfiguration,
    ): ResourceGridView|GridView {
        $requestConfiguration = $context->get(RequestConfigurationOption::class)?->requestConfiguration();
        $metadata = $context->get(MetadataOption::class)?->metadata();

        if (null === $requestConfiguration || null === $metadata) {
            return $this->decorated->create($grid, $context, $parameters, $driverConfiguration);
        }

        return $this->resourceGridViewFactory->create($grid, $parameters, $metadata, $requestConfiguration);
    }
}
