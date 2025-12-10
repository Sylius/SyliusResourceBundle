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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Bundle\ResourceBundle\Grid\Controller\ResourcesResolver;
use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParser;
use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigBulkActionGridRenderer;
use Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigBulkActionGridRenderer as TwigBulkActionGridRendererInterface;
use Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigGridRenderer;
use Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigGridRenderer as TwigGridRendererInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\LegacyGridViewFactory;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactory;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface;
use Sylius\Resource\Grid\View\Factory\GridViewFactory;
use Sylius\Resource\Grid\View\Factory\GridViewFactoryInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius.grid.resource_view_factory', ResourceGridViewFactory::class)
        ->args([
            service('sylius.grid.data_provider'),
            service('sylius.resource_controller.parameters_parser'),
        ]);

    $services->alias(ResourceGridViewFactoryInterface::class, 'sylius.grid.resource_view_factory');

    $services->set('sylius.resource_controller.resources_resolver.grid_aware', ResourcesResolver::class)
        ->decorate('sylius.resource_controller.resources_resolver', null, 256)
        ->args([
            service('sylius.resource_controller.resources_resolver.grid_aware.inner'),
            service('sylius.grid.provider'),
            service('sylius.grid.resource_view_factory'),
        ]);

    $services->alias(ResourcesResolverInterface::class, 'sylius.resource_controller.resources_resolver.grid_aware');

    $services->set('sylius.custom_grid_renderer.twig', TwigGridRenderer::class)
        ->decorate('sylius.grid.renderer.twig', null, 256)
        ->args([
            service('sylius.custom_grid_renderer.twig.inner'),
            service('twig'),
            service('sylius.grid_options_parser'),
            '%sylius.grid.templates.action%',
        ]);

    $services->alias(TwigGridRendererInterface::class, 'sylius.custom_grid_renderer.twig');

    $services->set('sylius.custom_bulk_action_grid_renderer.twig', TwigBulkActionGridRenderer::class)
        ->decorate('sylius.grid.bulk_action_renderer.twig', null, 256)
        ->args([
            service('twig'),
            service('sylius.grid_options_parser'),
            '%sylius.grid.templates.bulk_action%',
        ]);

    $services->alias(TwigBulkActionGridRendererInterface::class, 'sylius.custom_bulk_action_grid_renderer.twig');

    $services->set('sylius.grid_options_parser', OptionsParser::class)
        ->private()
        ->args([
            service('service_container'),
            service('sylius.expression_language'),
            service('property_accessor'),
        ]);

    $services->alias(OptionsParserInterface::class, 'sylius.grid_options_parser')
        ->private();

    $services->set('sylius.grid.view_factory.legacy', LegacyGridViewFactory::class)
        ->private()
        ->decorate('sylius.grid.view_factory.resource')
        ->args([
            service('sylius.grid.resource_view_factory'),
            service('.inner'),
        ]);

    $services->set('sylius.grid.view_factory.resource', GridViewFactory::class)
        ->args([service('sylius.grid.data_provider')]);

    $services->alias(GridViewFactoryInterface::class, 'sylius.grid.view_factory.resource');
};
