<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getQuery_Bus_Messenger_HandlersLocatorService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'query.bus.messenger.handlers_locator' shared service.
     *
     * @return \Symfony\Component\Messenger\Handler\HandlersLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/messenger/Handler/HandlersLocatorInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/messenger/Handler/HandlersLocator.php';

        return $container->privates['query.bus.messenger.handlers_locator'] = new \Symfony\Component\Messenger\Handler\HandlersLocator(['App\\BoardGameBlog\\Application\\Query\\FindBoardGameQuery' => new RewindableGenerator(function () use ($container) {
            yield 0 => ($container->privates['.messenger.handler_descriptor.O91e.KW'] ?? $container->load('get_Messenger_HandlerDescriptor_O91e_KWService'));
        }, 1), 'Symfony\\Component\\Messenger\\Message\\RedispatchMessage' => new RewindableGenerator(function () use ($container) {
            yield 0 => ($container->privates['.messenger.handler_descriptor.yp2tzbo'] ?? $container->load('get_Messenger_HandlerDescriptor_Yp2tzboService'));
        }, 1)]);
    }
}