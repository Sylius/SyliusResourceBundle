<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_Routing_Factory_OperationRoutePathFactory_UpdateService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'sylius.routing.factory.operation_route_path_factory.update' shared service.
     *
     * @return \Sylius\Component\Resource\Symfony\Routing\Factory\UpdateOperationRoutePathFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Routing/Factory/OperationRoutePathFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Routing/Factory/UpdateOperationRoutePathFactory.php';

        return $container->services['sylius.routing.factory.operation_route_path_factory.update'] = new \Sylius\Component\Resource\Symfony\Routing\Factory\UpdateOperationRoutePathFactory(($container->services['sylius.routing.factory.operation_route_path_factory.bulk_operation'] ?? $container->load('getSylius_Routing_Factory_OperationRoutePathFactory_BulkOperationService')));
    }
}
