<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_ResourceController_ResourcesCollectionProviderService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'sylius.resource_controller.resources_collection_provider' shared service.
     *
     * @return \Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProvider
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ResourcesCollectionProviderInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ResourcesCollectionProvider.php';
        include_once \dirname(__DIR__, 6).'/vendor/willdurand/hateoas/src/Representation/Factory/PagerfantaFactory.php';

        return $container->privates['sylius.resource_controller.resources_collection_provider'] = new \Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProvider(($container->services['sylius.resource_controller.resources_resolver.grid_aware'] ?? $container->load('getSylius_ResourceController_ResourcesResolver_GridAwareService')), ($container->privates['sylius.resource_controller.pagerfanta_representation_factory'] ??= new \Hateoas\Representation\Factory\PagerfantaFactory()));
    }
}
