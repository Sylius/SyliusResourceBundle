<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCascadeTransitionCallbackService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'SM\Callback\CascadeTransitionCallback' shared autowired service.
     *
     * @return \SM\Callback\CascadeTransitionCallback
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine/src/SM/Callback/CascadeTransitionCallback.php';

        return $container->services['SM\\Callback\\CascadeTransitionCallback'] = new \SM\Callback\CascadeTransitionCallback(($container->services['SM\\Factory\\FactoryInterface'] ?? $container->load('getFactoryInterfaceService')));
    }
}
