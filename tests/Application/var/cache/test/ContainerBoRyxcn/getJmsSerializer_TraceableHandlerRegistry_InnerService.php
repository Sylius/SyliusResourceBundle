<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getJmsSerializer_TraceableHandlerRegistry_InnerService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'jms_serializer.traceable_handler_registry.inner' shared service.
     *
     * @return \JMS\Serializer\Handler\LazyHandlerRegistry
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Handler/HandlerRegistryInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Handler/HandlerRegistry.php';
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Handler/LazyHandlerRegistry.php';

        return $container->privates['jms_serializer.traceable_handler_registry.inner'] = new \JMS\Serializer\Handler\LazyHandlerRegistry(($container->privates['jms_serializer.handler_registry.service_locator'] ?? $container->load('getJmsSerializer_HandlerRegistry_ServiceLocatorService')));
    }
}