<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_Grid_ServiceGridProviderService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'sylius.grid.service_grid_provider' shared service.
     *
     * @return \Sylius\Bundle\GridBundle\Provider\ServiceGridProvider
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Component/Provider/GridProviderInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Bundle/Provider/ServiceGridProvider.php';
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Component/Configuration/GridConfigurationExtenderInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Component/Configuration/GridConfigurationExtender.php';
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Component/Configuration/GridConfigurationRemovalsHandlerInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Component/Configuration/GridConfigurationRemovalsHandler.php';

        $a = ($container->services['sylius.grid.array_to_definition_converter'] ?? $container->load('getSylius_Grid_ArrayToDefinitionConverterService'));

        if (isset($container->services['sylius.grid.service_grid_provider'])) {
            return $container->services['sylius.grid.service_grid_provider'];
        }

        return $container->services['sylius.grid.service_grid_provider'] = new \Sylius\Bundle\GridBundle\Provider\ServiceGridProvider($a, ($container->services['sylius.grid.grid_registry'] ?? $container->load('getSylius_Grid_GridRegistryService')), ($container->services['sylius.grid.configuration_extender'] ??= new \Sylius\Component\Grid\Configuration\GridConfigurationExtender()), ($container->services['sylius.grid.configuration_removals_handler'] ??= new \Sylius\Component\Grid\Configuration\GridConfigurationRemovalsHandler()));
    }
}
