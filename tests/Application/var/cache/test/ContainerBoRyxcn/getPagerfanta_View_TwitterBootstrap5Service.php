<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getPagerfanta_View_TwitterBootstrap5Service extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'pagerfanta.view.twitter_bootstrap5' shared service.
     *
     * @return \Pagerfanta\View\TwitterBootstrap5View
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/ViewInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/View.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TemplateView.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TwitterBootstrapView.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TwitterBootstrap5View.php';

        return $container->privates['pagerfanta.view.twitter_bootstrap5'] = new \Pagerfanta\View\TwitterBootstrap5View();
    }
}