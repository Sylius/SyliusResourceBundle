<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Metadata;

use Sylius\Component\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;

/**
 * @experimental
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class ApplyStateMachineTransition extends HttpOperation implements UpdateOperationInterface
{
    public function __construct(
        ?array $methods = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $template = null,
        ?string $shortName = null,
        ?string $name = null,
        string|callable|null $provider = null,
        string|callable|null $processor = null,
        string|callable|null $responder = null,
        string|callable|null $repository = null,
        ?string $repositoryMethod = null,
        ?string $grid = null,
        ?bool $read = null,
        ?bool $write = null,
        ?bool $validate = null,
        ?string $formType = null,
        ?array $formOptions = null,
        ?string $stateMachineComponent = null,
        ?string $stateMachineTransition = null,
        ?string $stateMachineGraph = null,
        ?string $redirectToRoute = null,
    ) {
        parent::__construct(
            methods: $methods ?? ['PUT', 'PATCH'],
            path: $path,
            routePrefix: $routePrefix,
            template: $template,
            shortName: $shortName ?? $stateMachineTransition ?? 'apply_state_machine_transition',
            name: $name,
            provider: $provider,
            processor: $processor ?? ApplyStateMachineTransitionProcessor::class,
            responder: $responder,
            repository: $repository,
            repositoryMethod: $repositoryMethod,
            grid: $grid,
            read: $read,
            write: $write,
            validate: $validate ?? false,
            formType: $formType,
            formOptions: $formOptions,
            stateMachineComponent: $stateMachineComponent,
            stateMachineTransition: $stateMachineTransition,
            stateMachineGraph: $stateMachineGraph,
            redirectToRoute: $redirectToRoute,
        );
    }
}