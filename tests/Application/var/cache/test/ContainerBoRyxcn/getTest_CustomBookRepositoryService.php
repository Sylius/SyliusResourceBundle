<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getTest_CustomBookRepositoryService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'test.custom_book_repository' shared autowired service.
     *
     * @return \App\Repository\CustomBookRepository
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/src/Repository/CustomBookRepository.php';

        return $container->services['test.custom_book_repository'] = new \App\Repository\CustomBookRepository(($container->services['app.repository.book'] ?? $container->load('getApp_Repository_BookService')));
    }
}