<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getFactoryInterfaceService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'SM\Factory\FactoryInterface' shared autowired service.
     *
     * @return \SM\Factory\Factory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine/src/SM/Factory/FactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine/src/SM/Factory/ClearableFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine/src/SM/Factory/AbstractFactory.php';
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine/src/SM/Factory/Factory.php';
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine/src/SM/Callback/CallbackFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine/src/SM/Callback/CallbackFactory.php';
        include_once \dirname(__DIR__, 6).'/vendor/winzou/state-machine-bundle/Callback/ContainerAwareCallbackFactory.php';

        $a = ($container->services['event_dispatcher'] ?? self::getEventDispatcherService($container));

        if (isset($container->services['SM\\Factory\\FactoryInterface'])) {
            return $container->services['SM\\Factory\\FactoryInterface'];
        }

        return $container->services['SM\\Factory\\FactoryInterface'] = new \SM\Factory\Factory($container->parameters['sm.configs'], $a, ($container->services['SM\\Callback\\CallbackFactoryInterface'] ??= new \winzou\Bundle\StateMachineBundle\Callback\ContainerAwareCallbackFactory('winzou\\Bundle\\StateMachineBundle\\Callback\\ContainerAwareCallback', $container)));
    }
}