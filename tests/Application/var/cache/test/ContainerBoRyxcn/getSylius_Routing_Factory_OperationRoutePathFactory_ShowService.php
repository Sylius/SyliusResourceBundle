<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_Routing_Factory_OperationRoutePathFactory_ShowService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'sylius.routing.factory.operation_route_path_factory.show' shared service.
     *
     * @return \Sylius\Component\Resource\Symfony\Routing\Factory\ShowOperationRoutePathFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Routing/Factory/OperationRoutePathFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Routing/Factory/ShowOperationRoutePathFactory.php';

        return $container->services['sylius.routing.factory.operation_route_path_factory.show'] = new \Sylius\Component\Resource\Symfony\Routing\Factory\ShowOperationRoutePathFactory(($container->services['sylius.routing.factory.operation_route_path_factory.collection'] ?? $container->load('getSylius_Routing_Factory_OperationRoutePathFactory_CollectionService')));
    }
}
