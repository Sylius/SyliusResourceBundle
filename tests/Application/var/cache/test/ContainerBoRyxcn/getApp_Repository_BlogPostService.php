<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApp_Repository_BlogPostService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'app.repository.blog_post' shared service.
     *
     * @return \Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/persistence/src/Persistence/ObjectRepository.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/collections/lib/Doctrine/Common/Collections/Selectable.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/orm/lib/Doctrine/ORM/EntityRepository.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Repository/RepositoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Doctrine/ORM/ResourceRepositoryTrait.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Doctrine/ORM/EntityRepository.php';

        $a = ($container->services['doctrine.orm.default_entity_manager'] ?? self::getDoctrine_Orm_DefaultEntityManagerService($container));

        if (isset($container->services['app.repository.blog_post'])) {
            return $container->services['app.repository.blog_post'];
        }

        return $container->services['app.repository.blog_post'] = $a->getRepository('App\\Entity\\BlogPost');
    }
}
