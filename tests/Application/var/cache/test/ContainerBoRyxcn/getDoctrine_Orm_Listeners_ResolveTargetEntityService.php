<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getDoctrine_Orm_Listeners_ResolveTargetEntityService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'doctrine.orm.listeners.resolve_target_entity' shared service.
     *
     * @return \Doctrine\ORM\Tools\ResolveTargetEntityListener
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/event-manager/src/EventSubscriber.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/orm/lib/Doctrine/ORM/Tools/ResolveTargetEntityListener.php';

        $container->privates['doctrine.orm.listeners.resolve_target_entity'] = $instance = new \Doctrine\ORM\Tools\ResolveTargetEntityListener();

        $instance->addResolveTargetEntity('Sylius\\Component\\Resource\\Model\\TranslatableInterface', 'App\\Entity\\Book', []);
        $instance->addResolveTargetEntity('Sylius\\Component\\Resource\\Model\\TranslationInterface', 'App\\Entity\\BookTranslation', []);

        return $instance;
    }
}