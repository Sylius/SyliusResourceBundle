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

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->defaults()
        ->public();

    $services->set('sylius.grid.resource_view_factory', 'Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactory')
        ->args([
            service('sylius.grid.data_provider'),
            service('sylius.resource_controller.parameters_parser'),
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface', 'sylius.grid.resource_view_factory');

    $services->set('sylius.resource_controller.resources_resolver.grid_aware', 'Sylius\Bundle\ResourceBundle\Grid\Controller\ResourcesResolver')
        ->decorate('sylius.resource_controller.resources_resolver', null, 256)
        ->args([
            service('sylius.resource_controller.resources_resolver.grid_aware.inner'),
            service('sylius.grid.provider'),
            service('sylius.grid.resource_view_factory'),
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface', 'sylius.resource_controller.resources_resolver.grid_aware');

    $services->set('sylius.custom_grid_renderer.twig', 'Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigGridRenderer')
        ->decorate('sylius.grid.renderer.twig', null, 256)
        ->args([
            service('sylius.custom_grid_renderer.twig.inner'),
            service('twig'),
            service('sylius.grid_options_parser'),
            '%sylius.grid.templates.action%',
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigGridRenderer', 'sylius.custom_grid_renderer.twig');

    $services->set('sylius.custom_bulk_action_grid_renderer.twig', 'Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigBulkActionGridRenderer')
        ->decorate('sylius.grid.bulk_action_renderer.twig', null, 256)
        ->args([
            service('twig'),
            service('sylius.grid_options_parser'),
            '%sylius.grid.templates.bulk_action%',
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigBulkActionGridRenderer', 'sylius.custom_bulk_action_grid_renderer.twig');

    $services->set('sylius.grid_options_parser', 'Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParser')
        ->private()
        ->args([
            service('service_container'),
            service('sylius.expression_language'),
            service('property_accessor'),
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface', 'sylius.grid_options_parser')
        ->private();

    $services->set('sylius.grid.view_factory.legacy', 'Sylius\Bundle\ResourceBundle\Grid\View\LegacyGridViewFactory')
        ->private()
        ->decorate('sylius.grid.view_factory.resource')
        ->args([
            service('sylius.grid.resource_view_factory'),
            service('.inner'),
        ]);

    $services->set('sylius.grid.view_factory.resource', 'Sylius\Resource\Grid\View\Factory\GridViewFactory')
        ->args([service('sylius.grid.data_provider')]);

    $services->alias('Sylius\Resource\Grid\View\Factory\GridViewFactoryInterface', 'sylius.grid.view_factory.resource');
};
