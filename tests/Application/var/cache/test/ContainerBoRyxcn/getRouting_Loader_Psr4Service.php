<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getRouting_Loader_Psr4Service extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'routing.loader.psr4' shared service.
     *
     * @return \Symfony\Component\Routing\Loader\Psr4DirectoryLoader
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/config/Loader/LoaderInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/config/Loader/Loader.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/config/Loader/DirectoryAwareLoaderInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/routing/Loader/Psr4DirectoryLoader.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/config/FileLocatorInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/config/FileLocator.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/http-kernel/Config/FileLocator.php';

        return $container->privates['routing.loader.psr4'] = new \Symfony\Component\Routing\Loader\Psr4DirectoryLoader(($container->privates['file_locator'] ??= new \Symfony\Component\HttpKernel\Config\FileLocator(($container->services['kernel'] ?? $container->get('kernel', 1)))));
    }
}
