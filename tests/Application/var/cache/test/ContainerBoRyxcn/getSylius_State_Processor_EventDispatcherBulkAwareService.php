<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_State_Processor_EventDispatcherBulkAwareService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'sylius.state.processor.event_dispatcher_bulk_aware' shared service.
     *
     * @return \Sylius\Component\Resource\State\EventDispatcherBulkAwareProcessor
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/State/ProcessorInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/State/EventDispatcherBulkAwareProcessor.php';

        $a = ($container->privates['sylius.state.processor.event_dispatcher_bulk_aware.inner'] ?? $container->load('getSylius_State_Processor_EventDispatcherBulkAware_InnerService'));

        if (isset($container->privates['sylius.state.processor.event_dispatcher_bulk_aware'])) {
            return $container->privates['sylius.state.processor.event_dispatcher_bulk_aware'];
        }
        $b = ($container->privates['sylius.dispatcher.operation'] ?? self::getSylius_Dispatcher_OperationService($container));

        if (isset($container->privates['sylius.state.processor.event_dispatcher_bulk_aware'])) {
            return $container->privates['sylius.state.processor.event_dispatcher_bulk_aware'];
        }

        return $container->privates['sylius.state.processor.event_dispatcher_bulk_aware'] = new \Sylius\Component\Resource\State\EventDispatcherBulkAwareProcessor($a, $b);
    }
}
