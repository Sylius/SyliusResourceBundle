<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getPagerfanta_EventListener_ConvertNotValidCurrentPageToNotFoundService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'pagerfanta.event_listener.convert_not_valid_current_page_to_not_found' shared service.
     *
     * @return \BabDev\PagerfantaBundle\EventListener\ConvertNotValidMaxPerPageToNotFoundListener
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/babdev/pagerfanta-bundle/src/EventListener/ConvertNotValidMaxPerPageToNotFoundListener.php';

        return $container->privates['pagerfanta.event_listener.convert_not_valid_current_page_to_not_found'] = new \BabDev\PagerfantaBundle\EventListener\ConvertNotValidMaxPerPageToNotFoundListener();
    }
}