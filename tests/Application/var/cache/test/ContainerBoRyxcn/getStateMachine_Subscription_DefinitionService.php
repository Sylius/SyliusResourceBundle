<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getStateMachine_Subscription_DefinitionService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'state_machine.subscription.definition' shared service.
     *
     * @return \Symfony\Component\Workflow\Definition
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/workflow/Definition.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/workflow/Transition.php';

        return $container->privates['state_machine.subscription.definition'] = new \Symfony\Component\Workflow\Definition(['new', 'accepted', 'rejected'], [new \Symfony\Component\Workflow\Transition('accept', 'new', 'accepted'), new \Symfony\Component\Workflow\Transition('reject', 'new', 'rejected')], ['new'], ($container->privates['state_machine.subscription.metadata_store'] ?? $container->load('getStateMachine_Subscription_MetadataStoreService')));
    }
}