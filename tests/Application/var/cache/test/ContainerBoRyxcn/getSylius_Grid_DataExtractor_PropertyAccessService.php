<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_Grid_DataExtractor_PropertyAccessService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'sylius.grid.data_extractor.property_access' shared service.
     *
     * @return \Sylius\Component\Grid\DataExtractor\PropertyAccessDataExtractor
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Component/DataExtractor/DataExtractorInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Component/DataExtractor/PropertyAccessDataExtractor.php';

        return $container->services['sylius.grid.data_extractor.property_access'] = new \Sylius\Component\Grid\DataExtractor\PropertyAccessDataExtractor(($container->privates['property_accessor'] ?? self::getPropertyAccessorService($container)));
    }
}