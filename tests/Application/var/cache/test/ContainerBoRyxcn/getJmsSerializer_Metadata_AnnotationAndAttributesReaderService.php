<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getJmsSerializer_Metadata_AnnotationAndAttributesReaderService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'jms_serializer.metadata.annotation_and_attributes_reader' shared service.
     *
     * @return \JMS\Serializer\Metadata\Driver\AttributeDriver\AttributeReader
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Metadata/Driver/AttributeDriver/AttributeReader.php';

        return $container->privates['jms_serializer.metadata.annotation_and_attributes_reader'] = new \JMS\Serializer\Metadata\Driver\AttributeDriver\AttributeReader(($container->privates['annotations.cached_reader'] ?? self::getAnnotations_CachedReaderService($container)));
    }
}
