<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApp_Factory_GedmoService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'app.factory.gedmo' shared service.
     *
     * @return \Sylius\Component\Resource\Factory\Factory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/Factory/FactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Factory/Factory.php';

        return $container->services['app.factory.gedmo'] = new \Sylius\Component\Resource\Factory\Factory('App\\Entity\\GedmoExtendedExample');
    }
}
