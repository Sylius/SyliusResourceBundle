<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getTwig_Loader_NativeFilesystemService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'twig.loader.native_filesystem' shared service.
     *
     * @return \Twig\Loader\FilesystemLoader
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/twig/twig/src/Loader/LoaderInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/twig/twig/src/Loader/FilesystemLoader.php';

        $container->privates['twig.loader.native_filesystem'] = $instance = new \Twig\Loader\FilesystemLoader([], \dirname(__DIR__, 4));

        $instance->addPath((\dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Twig/templates/'), 'Pagerfanta');
        $instance->addPath((\dirname(__DIR__, 4).'/templates'));
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/symfony/security-bundle/Resources/views'), 'Security');
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/symfony/security-bundle/Resources/views'), '!Security');
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/doctrine/doctrine-bundle/Resources/views'), 'Doctrine');
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/doctrine/doctrine-bundle/Resources/views'), '!Doctrine');
        $instance->addPath((\dirname(__DIR__, 6).'/src/Bundle/Resources/views'), 'SyliusResource');
        $instance->addPath((\dirname(__DIR__, 6).'/src/Bundle/Resources/views'), '!SyliusResource');
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/babdev/pagerfanta-bundle/templates'), 'BabDevPagerfanta');
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/babdev/pagerfanta-bundle/templates'), '!BabDevPagerfanta');
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/jms/serializer-bundle/Resources/views'), 'JMSSerializer');
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/jms/serializer-bundle/Resources/views'), '!JMSSerializer');
        $instance->addPath((\dirname(__DIR__, 4).'/templates'));
        $instance->addPath((\dirname(__DIR__, 6).'/vendor/symfony/twig-bridge/Resources/views/Form'));

        return $instance;
    }
}
