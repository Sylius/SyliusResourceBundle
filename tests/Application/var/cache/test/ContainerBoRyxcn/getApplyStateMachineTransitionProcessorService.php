<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApplyStateMachineTransitionProcessorService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'Sylius\Component\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor' shared service.
     *
     * @return \Sylius\Component\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/State/ProcessorInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/StateMachine/State/ApplyStateMachineTransitionProcessor.php';

        $a = ($container->privates['sylius.state_machine.operation'] ?? $container->load('getSylius_StateMachine_OperationService'));

        if (isset($container->privates['Sylius\\Component\\Resource\\StateMachine\\State\\ApplyStateMachineTransitionProcessor'])) {
            return $container->privates['Sylius\\Component\\Resource\\StateMachine\\State\\ApplyStateMachineTransitionProcessor'];
        }

        return $container->privates['Sylius\\Component\\Resource\\StateMachine\\State\\ApplyStateMachineTransitionProcessor'] = new \Sylius\Component\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor($a, ($container->privates['Sylius\\Component\\Resource\\Doctrine\\Common\\State\\PersistProcessor'] ?? $container->load('getPersistProcessorService')));
    }
}
