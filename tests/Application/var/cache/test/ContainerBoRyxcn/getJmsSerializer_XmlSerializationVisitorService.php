<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getJmsSerializer_XmlSerializationVisitorService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'jms_serializer.xml_serialization_visitor' shared service.
     *
     * @return \JMS\Serializer\Visitor\Factory\XmlSerializationVisitorFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Visitor/Factory/SerializationVisitorFactory.php';
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Visitor/Factory/XmlSerializationVisitorFactory.php';

        $container->privates['jms_serializer.xml_serialization_visitor'] = $instance = new \JMS\Serializer\Visitor\Factory\XmlSerializationVisitorFactory();

        $instance->setFormatOutput(true);

        return $instance;
    }
}
