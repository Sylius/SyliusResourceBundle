<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_Container_Private_Pagerfanta_ViewFactoryService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public '.container.private.pagerfanta.view_factory' shared service.
     *
     * @return \Pagerfanta\View\ViewFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/ViewFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/ViewFactory.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/ViewInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/View.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TemplateView.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/DefaultView.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/Foundation6View.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/SemanticUiView.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TwitterBootstrapView.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TwitterBootstrap3View.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TwitterBootstrap4View.php';
        include_once \dirname(__DIR__, 6).'/vendor/pagerfanta/pagerfanta/lib/Core/View/TwitterBootstrap5View.php';

        $container->services['.container.private.pagerfanta.view_factory'] = $instance = new \Pagerfanta\View\ViewFactory();

        $instance->set('default', ($container->privates['pagerfanta.view.default'] ??= new \Pagerfanta\View\DefaultView()));
        $instance->set('foundation6', ($container->privates['pagerfanta.view.foundation6'] ??= new \Pagerfanta\View\Foundation6View()));
        $instance->set('semantic_ui', ($container->privates['pagerfanta.view.semantic_ui'] ??= new \Pagerfanta\View\SemanticUiView()));
        $instance->set('twitter_bootstrap', ($container->privates['pagerfanta.view.twitter_bootstrap'] ??= new \Pagerfanta\View\TwitterBootstrapView()));
        $instance->set('twitter_bootstrap3', ($container->privates['pagerfanta.view.twitter_bootstrap3'] ??= new \Pagerfanta\View\TwitterBootstrap3View()));
        $instance->set('twitter_bootstrap4', ($container->privates['pagerfanta.view.twitter_bootstrap4'] ??= new \Pagerfanta\View\TwitterBootstrap4View()));
        $instance->set('twitter_bootstrap5', ($container->privates['pagerfanta.view.twitter_bootstrap5'] ??= new \Pagerfanta\View\TwitterBootstrap5View()));
        $instance->set('twig', ($container->privates['pagerfanta.view.twig'] ?? $container->load('getPagerfanta_View_TwigService')));

        return $instance;
    }
}
