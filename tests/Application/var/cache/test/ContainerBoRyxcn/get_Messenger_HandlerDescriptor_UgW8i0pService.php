<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_Messenger_HandlerDescriptor_UgW8i0pService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private '.messenger.handler_descriptor.ugW8i0p' shared service.
     *
     * @return \Symfony\Component\Messenger\Handler\HandlerDescriptor
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/messenger/Handler/HandlerDescriptor.php';

        $a = ($container->privates['messenger.redispatch_message_handler'] ?? $container->load('getMessenger_RedispatchMessageHandlerService'));

        if (isset($container->privates['.messenger.handler_descriptor.ugW8i0p'])) {
            return $container->privates['.messenger.handler_descriptor.ugW8i0p'];
        }

        return $container->privates['.messenger.handler_descriptor.ugW8i0p'] = new \Symfony\Component\Messenger\Handler\HandlerDescriptor($a, []);
    }
}