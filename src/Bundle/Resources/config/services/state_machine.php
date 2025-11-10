<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Resource\StateMachine\OperationStateMachine;
use Sylius\Resource\StateMachine\OperationStateMachineInterface;
use Sylius\Resource\Symfony\Workflow\OperationStateMachine as SymfonyOperationStateMachine;
use Sylius\Resource\Winzou\StateMachine\OperationStateMachine as WinzouOperationStateMachine;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.state_machine.operation', OperationStateMachine::class)
        ->args([tagged_locator('sylius_resource.state_machine', indexAttribute: 'key')]);

    $services->alias(OperationStateMachineInterface::class, 'sylius.state_machine.operation');

    $services->alias('sylius.state_machine.operation.default', 'sylius.state_machine.operation.winzou');

    $services->set('sylius.state_machine.operation.symfony', SymfonyOperationStateMachine::class)
        ->args([service('workflow.registry')->nullOnInvalid()])
        ->tag('sylius_resource.state_machine', ['key' => 'symfony']);

    $services->set('sylius.state_machine.operation.winzou', WinzouOperationStateMachine::class)
        ->args([service('sm.factory')->nullOnInvalid()])
        ->tag('sylius_resource.state_machine', ['key' => 'winzou']);
};
