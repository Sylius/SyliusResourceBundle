<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getFosRest_Serializer_FormErrorHandler_InnerService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'fos_rest.serializer.form_error_handler.inner' shared service.
     *
     * @return \JMS\Serializer\Handler\FormErrorHandler
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Handler/SubscribingHandlerInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Handler/FormErrorHandler.php';

        return $container->privates['fos_rest.serializer.form_error_handler.inner'] = new \JMS\Serializer\Handler\FormErrorHandler(($container->services['translator'] ?? self::getTranslatorService($container)), 'validators');
    }
}
