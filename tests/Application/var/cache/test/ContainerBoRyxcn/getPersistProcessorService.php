<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getPersistProcessorService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor' shared service.
     *
     * @return \Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/State/ProcessorInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Reflection/ClassInfoTrait.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Doctrine/Common/State/PersistProcessor.php';

        return $container->privates['Sylius\\Component\\Resource\\Doctrine\\Common\\State\\PersistProcessor'] = new \Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor(($container->services['doctrine'] ?? self::getDoctrineService($container)));
    }
}