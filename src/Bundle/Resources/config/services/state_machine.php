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

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('sylius.state_machine.operation', 'Sylius\Resource\StateMachine\OperationStateMachine')
        ->args([tagged_locator('sylius_resource.state_machine', indexAttribute: 'key')]);

    $services->alias('Sylius\Resource\StateMachine\OperationStateMachineInterface', 'sylius.state_machine.operation');

    $services->alias('sylius.state_machine.operation.default', 'sylius.state_machine.operation.winzou');

    $services->set('sylius.state_machine.operation.symfony', 'Sylius\Resource\Symfony\Workflow\OperationStateMachine')
        ->args([service('workflow.registry')->nullOnInvalid()])
        ->tag('sylius_resource.state_machine', ['key' => 'symfony']);

    $services->set('sylius.state_machine.operation.winzou', 'Sylius\Resource\Winzou\StateMachine\OperationStateMachine')
        ->args([service('sm.factory')->nullOnInvalid()])
        ->tag('sylius_resource.state_machine', ['key' => 'winzou']);
};
