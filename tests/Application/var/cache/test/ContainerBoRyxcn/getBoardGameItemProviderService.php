<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getBoardGameItemProviderService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider\BoardGameItemProvider' shared autowired service.
     *
     * @return \App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider\BoardGameItemProvider
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/src/BoardGameBlog/Infrastructure/Sylius/State/Http/Provider/BoardGameItemProvider.php';

        $a = ($container->privates['App\\Shared\\Infrastructure\\Symfony\\Messenger\\MessengerQueryBus'] ?? $container->load('getMessengerQueryBusService'));

        if (isset($container->privates['App\\BoardGameBlog\\Infrastructure\\Sylius\\State\\Http\\Provider\\BoardGameItemProvider'])) {
            return $container->privates['App\\BoardGameBlog\\Infrastructure\\Sylius\\State\\Http\\Provider\\BoardGameItemProvider'];
        }

        return $container->privates['App\\BoardGameBlog\\Infrastructure\\Sylius\\State\\Http\\Provider\\BoardGameItemProvider'] = new \App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider\BoardGameItemProvider($a);
    }
}
