<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getJmsSerializer_EventDispatcher_ServiceLocatorService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'jms_serializer.event_dispatcher.service_locator' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['jms_serializer.event_dispatcher.service_locator'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'jms_serializer.traceable_runs_listener' => ['privates', 'jms_serializer.traceable_runs_listener', 'getJmsSerializer_TraceableRunsListenerService', true],
            'jms_serializer.doctrine_proxy_subscriber' => ['privates', 'jms_serializer.doctrine_proxy_subscriber', 'getJmsSerializer_DoctrineProxySubscriberService', true],
            'hateoas.event_listener.xml' => ['services', 'hateoas.event_listener.xml', 'getHateoas_EventListener_XmlService', true],
            'hateoas.event_listener.json' => ['services', 'hateoas.event_listener.json', 'getHateoas_EventListener_JsonService', true],
        ], [
            'jms_serializer.traceable_runs_listener' => '?',
            'jms_serializer.doctrine_proxy_subscriber' => '?',
            'hateoas.event_listener.xml' => '?',
            'hateoas.event_listener.json' => '?',
        ]);
    }
}
