<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSylius_StateMachine_OperationService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'sylius.state_machine.operation' shared service.
     *
     * @return \Sylius\Component\Resource\StateMachine\OperationStateMachine
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Component/StateMachine/OperationStateMachineInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/StateMachine/OperationStateMachine.php';

        return $container->privates['sylius.state_machine.operation'] = new \Sylius\Component\Resource\StateMachine\OperationStateMachine(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'symfony' => ['privates', 'sylius.state_machine.operation.symfony', 'getSylius_StateMachine_Operation_SymfonyService', true],
            'winzou' => ['privates', 'sylius.state_machine.operation.winzou', 'getSylius_StateMachine_Operation_WinzouService', true],
        ], [
            'symfony' => 'Sylius\\Component\\Resource\\Symfony\\Workflow\\OperationStateMachine',
            'winzou' => 'Sylius\\Component\\Resource\\Winzou\\StateMachine\\OperationStateMachine',
        ]));
    }
}
