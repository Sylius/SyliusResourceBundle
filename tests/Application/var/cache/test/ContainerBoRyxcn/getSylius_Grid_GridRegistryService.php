<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_Grid_GridRegistryService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'sylius.grid.grid_registry' shared service.
     *
     * @return \Sylius\Bundle\GridBundle\Registry\GridRegistry
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Bundle/Registry/GridRegistryInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Bundle/Registry/GridRegistry.php';

        return $container->services['sylius.grid.grid_registry'] = new \Sylius\Bundle\GridBundle\Registry\GridRegistry(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'app_board_game' => ['privates', 'App\\BoardGameBlog\\Infrastructure\\Sylius\\Grid\\BoardGameGrid', 'getBoardGameGridService', true],
            'app_subscription' => ['privates', 'App\\Subscription\\Grid\\SubscriptionGrid', 'getSubscriptionGridService', true],
        ], [
            'app_board_game' => 'App\\BoardGameBlog\\Infrastructure\\Sylius\\Grid\\BoardGameGrid',
            'app_subscription' => 'App\\Subscription\\Grid\\SubscriptionGrid',
        ]));
    }
}
