<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getDoctrineBoardGameRepositoryService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'App\BoardGameBlog\Infrastructure\Doctrine\DoctrineBoardGameRepository' shared autowired service.
     *
     * @return \App\BoardGameBlog\Infrastructure\Doctrine\DoctrineBoardGameRepository
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/persistence/src/Persistence/ObjectRepository.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/collections/lib/Doctrine/Common/Collections/Selectable.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/orm/lib/Doctrine/ORM/EntityRepository.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/doctrine-bundle/Repository/ServiceEntityRepositoryInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/var-exporter/Internal/LazyObjectTrait.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/var-exporter/LazyGhostTrait.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/doctrine-bundle/Repository/LazyServiceEntityRepository.php';
        include_once \dirname(__DIR__, 6).'/vendor/doctrine/doctrine-bundle/Repository/ServiceEntityRepository.php';
        include_once \dirname(__DIR__, 4).'/src/Shared/Domain/Repository/RepositoryInterface.php';
        include_once \dirname(__DIR__, 4).'/src/BoardGameBlog/Domain/Repository/BoardGameRepositoryInterface.php';
        include_once \dirname(__DIR__, 4).'/src/BoardGameBlog/Infrastructure/Doctrine/DoctrineBoardGameRepository.php';

        return $container->privates['App\\BoardGameBlog\\Infrastructure\\Doctrine\\DoctrineBoardGameRepository'] = new \App\BoardGameBlog\Infrastructure\Doctrine\DoctrineBoardGameRepository(($container->services['doctrine'] ?? self::getDoctrineService($container)));
    }
}
