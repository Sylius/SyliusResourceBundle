<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getNelmioAlice_FileLocator_DefaultService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'nelmio_alice.file_locator.default' shared service.
     *
     * @return \Nelmio\Alice\FileLocator\DefaultFileLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/nelmio/alice/src/FileLocatorInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/nelmio/alice/src/FileLocator/DefaultFileLocator.php';

        return $container->privates['nelmio_alice.file_locator.default'] = new \Nelmio\Alice\FileLocator\DefaultFileLocator();
    }
}
