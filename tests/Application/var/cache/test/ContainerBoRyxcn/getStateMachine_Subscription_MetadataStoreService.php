<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getStateMachine_Subscription_MetadataStoreService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'state_machine.subscription.metadata_store' shared service.
     *
     * @return \Symfony\Component\Workflow\Metadata\InMemoryMetadataStore
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/workflow/Metadata/MetadataStoreInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/workflow/Metadata/GetMetadataTrait.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/workflow/Metadata/InMemoryMetadataStore.php';

        return $container->privates['state_machine.subscription.metadata_store'] = new \Symfony\Component\Workflow\Metadata\InMemoryMetadataStore([], [], new \SplObjectStorage());
    }
}
