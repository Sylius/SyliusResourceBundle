<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getJmsSerializer_XmlDeserializationVisitorService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'jms_serializer.xml_deserialization_visitor' shared service.
     *
     * @return \JMS\Serializer\Visitor\Factory\XmlDeserializationVisitorFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Visitor/Factory/DeserializationVisitorFactory.php';
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Visitor/Factory/XmlDeserializationVisitorFactory.php';

        return $container->privates['jms_serializer.xml_deserialization_visitor'] = new \JMS\Serializer\Visitor\Factory\XmlDeserializationVisitorFactory();
    }
}
