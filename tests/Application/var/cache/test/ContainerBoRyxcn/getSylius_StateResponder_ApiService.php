<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_StateResponder_ApiService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'sylius.state_responder.api' shared service.
     *
     * @return \Sylius\Component\Resource\Symfony\Request\State\ApiResponder
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/State/ResponderInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Request/State/ApiResponder.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Response/HeadersInitiatorInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Symfony/Response/ApiHeadersInitiator.php';

        return $container->privates['sylius.state_responder.api'] = new \Sylius\Component\Resource\Symfony\Request\State\ApiResponder(($container->privates['sylius.headers_initiator.api'] ??= new \Sylius\Component\Resource\Symfony\Response\ApiHeadersInitiator()));
    }
}
