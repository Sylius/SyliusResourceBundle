<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConsole_Command_MessengerDebugService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'console.command.messenger_debug' shared service.
     *
     * @return \Symfony\Component\Messenger\Command\DebugCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/messenger/Command/DebugCommand.php';

        $container->privates['console.command.messenger_debug'] = $instance = new \Symfony\Component\Messenger\Command\DebugCommand(['command.bus' => ['App\\BoardGameBlog\\Application\\Command\\CreateBoardGameCommand' => [['App\\BoardGameBlog\\Application\\Command\\CreateBoardGameCommandHandler', []]], 'App\\BoardGameBlog\\Application\\Command\\DeleteBoardGameCommand' => [['App\\BoardGameBlog\\Application\\Command\\DeleteBoardGameCommandHandler', []]], 'App\\BoardGameBlog\\Application\\Command\\UpdateBoardGameCommand' => [['App\\BoardGameBlog\\Application\\Command\\UpdateBoardGameCommandHandler', []]], 'Symfony\\Component\\Messenger\\Message\\RedispatchMessage' => [['messenger.redispatch_message_handler', []]]], 'query.bus' => ['App\\BoardGameBlog\\Application\\Query\\FindBoardGameQuery' => [['App\\BoardGameBlog\\Application\\Query\\FindBoardGameQueryHandler', []]], 'Symfony\\Component\\Messenger\\Message\\RedispatchMessage' => [['messenger.redispatch_message_handler', []]]]]);

        $instance->setName('debug:messenger');
        $instance->setDescription('List messages you can dispatch using the message buses');

        return $instance;
    }
}
