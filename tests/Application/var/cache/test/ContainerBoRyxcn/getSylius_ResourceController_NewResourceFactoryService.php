<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_ResourceController_NewResourceFactoryService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'sylius.resource_controller.new_resource_factory' shared service.
     *
     * @return \Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/NewResourceFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/NewResourceFactory.php';

        return $container->privates['sylius.resource_controller.new_resource_factory'] = new \Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory();
    }
}
