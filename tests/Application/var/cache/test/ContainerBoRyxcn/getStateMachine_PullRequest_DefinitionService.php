<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getStateMachine_PullRequest_DefinitionService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'state_machine.pull_request.definition' shared service.
     *
     * @return \Symfony\Component\Workflow\Definition
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/workflow/Definition.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/workflow/Transition.php';

        return $container->privates['state_machine.pull_request.definition'] = new \Symfony\Component\Workflow\Definition(['start', 'coding', 'test', 'review', 'merged', 'closed'], [new \Symfony\Component\Workflow\Transition('submit', 'start', 'test'), new \Symfony\Component\Workflow\Transition('update', 'coding', 'test'), new \Symfony\Component\Workflow\Transition('update', 'test', 'test'), new \Symfony\Component\Workflow\Transition('update', 'review', 'test'), new \Symfony\Component\Workflow\Transition('wait_for_review', 'test', 'review'), new \Symfony\Component\Workflow\Transition('request_change', 'review', 'coding'), new \Symfony\Component\Workflow\Transition('accept', 'review', 'merged'), new \Symfony\Component\Workflow\Transition('reject', 'review', 'closed'), new \Symfony\Component\Workflow\Transition('reopen', 'closed', 'review')], ['start'], ($container->privates['state_machine.pull_request.metadata_store'] ?? $container->load('getStateMachine_PullRequest_MetadataStoreService')));
    }
}