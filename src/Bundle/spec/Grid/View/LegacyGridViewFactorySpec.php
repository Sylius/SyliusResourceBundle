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

namespace spec\Sylius\Bundle\ResourceBundle\Grid\View;

use PhpSpec\ObjectBehavior;
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

final class LegacyGridViewFactorySpec extends ObjectBehavior
{
    function let(
        ResourceGridViewFactoryInterface $resourceGridViewFactory,
        GridViewFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($resourceGridViewFactory, $decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(LegacyGridViewFactory::class);
    }

    function it_creates_a_legacy_resource_grid_view(
        Grid $grid,
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        ResourceGridViewFactoryInterface $resourceGridViewFactory,
        ResourceGridView $resourceGridView,
    ): void {
        $context = new Context(
            new RequestConfigurationOption($requestConfiguration->getWrappedObject()),
            new MetadataOption($metadata->getWrappedObject()),
        );

        $parameters = new Parameters();

        $resourceGridViewFactory->create(
            $grid,
            $parameters,
            $metadata,
            $requestConfiguration,
        )->willReturn($resourceGridView)->shouldBeCalled();

        $this->create($grid, $context, $parameters, [])->shouldReturn($resourceGridView);
    }

    function it_creates_a_grid_view_when_context_has_no_request_configuration(
        Grid $grid,
        GridViewFactoryInterface $decorated,
        MetadataInterface $metadata,
        GridView $gridView,
    ): void {
        $context = new Context(
            new MetadataOption($metadata->getWrappedObject()),
        );

        $parameters = new Parameters();

        $decorated->create($grid, $context, $parameters, [])->willReturn($gridView)->shouldBeCalled();

        $this->create($grid, $context, $parameters, [])->shouldReturn($gridView);
    }
}
