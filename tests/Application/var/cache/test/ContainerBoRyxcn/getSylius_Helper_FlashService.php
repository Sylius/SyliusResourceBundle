<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_Helper_FlashService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'sylius.helper.flash' shared service.
     *
     * @return \Sylius\Component\Resource\Symfony\Session\Flash\FlashHelper
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Session/Flash/FlashHelperInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Session/Flash/FlashHelper.php';

        return $container->privates['sylius.helper.flash'] = new \Sylius\Component\Resource\Symfony\Session\Flash\FlashHelper(($container->services['translator'] ?? self::getTranslatorService($container)));
    }
}