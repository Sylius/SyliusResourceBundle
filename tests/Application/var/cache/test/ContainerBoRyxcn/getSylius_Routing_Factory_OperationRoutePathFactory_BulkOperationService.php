<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_Routing_Factory_OperationRoutePathFactory_BulkOperationService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'sylius.routing.factory.operation_route_path_factory.bulk_operation' shared service.
     *
     * @return \Sylius\Component\Resource\Symfony\Routing\Factory\BulkOperationRoutePathFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Routing/Factory/OperationRoutePathFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Routing/Factory/BulkOperationRoutePathFactory.php';

        return $container->services['sylius.routing.factory.operation_route_path_factory.bulk_operation'] = new \Sylius\Component\Resource\Symfony\Routing\Factory\BulkOperationRoutePathFactory(($container->services['sylius.routing.factory.operation_route_path_factory.delete'] ?? $container->load('getSylius_Routing_Factory_OperationRoutePathFactory_DeleteService')));
    }
}