<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getQuery_Bus_Middleware_SendMessageService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'query.bus.middleware.send_message' shared service.
     *
     * @return \Symfony\Component\Messenger\Middleware\SendMessageMiddleware
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/messenger/Middleware/MiddlewareInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/messenger/Middleware/SendMessageMiddleware.php';

        $a = ($container->privates['messenger.senders_locator'] ?? $container->load('getMessenger_SendersLocatorService'));

        if (isset($container->privates['query.bus.middleware.send_message'])) {
            return $container->privates['query.bus.middleware.send_message'];
        }
        $b = ($container->services['event_dispatcher'] ?? self::getEventDispatcherService($container));

        if (isset($container->privates['query.bus.middleware.send_message'])) {
            return $container->privates['query.bus.middleware.send_message'];
        }

        $container->privates['query.bus.middleware.send_message'] = $instance = new \Symfony\Component\Messenger\Middleware\SendMessageMiddleware($a, $b, true);

        $instance->setLogger(($container->privates['Psr\\Log\\NullLogger'] ??= new \Psr\Log\NullLogger()));

        return $instance;
    }
}
