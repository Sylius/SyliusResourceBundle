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
    $container->import('state/processor.php');
    $container->import('state/provider.php');
    $container->import('state/**/*.php');

    $services->set('sylius.resource_factory.expression_language', \Symfony\Component\ExpressionLanguage\ExpressionLanguage::class);

    $services->set('sylius.state_provider.locator', 'Sylius\Resource\State\Provider')
        ->args([tagged_locator('sylius.state_provider')]);

    $services->alias('Sylius\Resource\State\ProviderInterface', 'sylius.state_provider');

    $services->set('sylius.state_factory', 'Sylius\Resource\State\Factory')
        ->args([
            tagged_locator('sylius.resource_factory'),
            service('sylius.expression_language.argument_parser.factory'),
        ]);

    $services->alias('Sylius\Resource\State\FactoryInterface', 'sylius.state_factory');

    $services->set('sylius.state_responder', 'Sylius\Resource\State\Responder')
        ->args([tagged_locator('sylius.state_responder')]);

    $services->alias('Sylius\Resource\State\ResponderInterface', 'sylius.state_responder');

    $services->set('Sylius\Resource\Symfony\Request\State\Provider')
        ->args([
            tagged_locator('sylius.repository'),
            service('sylius.repository_argument_resolver.request'),
            service('sylius.expression_language.argument_parser.repository'),
        ])
        ->tag('sylius.state_provider');

    $services->set('Sylius\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor')
        ->args([
            service('sylius.state_machine.operation'),
            service('Sylius\Resource\Doctrine\Common\State\PersistProcessor')->nullOnInvalid(),
        ])
        ->tag('sylius.state_processor');

    $services->set('Sylius\Resource\Symfony\Request\State\Responder')
        ->args([tagged_locator('sylius.state_responder')])
        ->tag('sylius.state_responder');

    $services->set('sylius.state_responder.html', 'Sylius\Resource\Symfony\Request\State\TwigResponder')
        ->args([
            service('sylius.routing.redirect_handler'),
            service('sylius.twig.context.factory'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sylius.state_responder');

    $services->set('sylius.headers_initiator.api', 'Sylius\Resource\Symfony\Response\ApiHeadersInitiator');

    $services->set('sylius.state_responder.api', 'Sylius\Resource\Symfony\Request\State\ApiResponder')
        ->args([service('sylius.headers_initiator.api')])
        ->tag('sylius.state_responder');

    $services->set('Sylius\Resource\Grid\State\RequestGridProvider')
        ->args([
            service('sylius.grid.view_factory.resource')->nullOnInvalid(),
            service('sylius.grid.provider')->nullOnInvalid(),
        ])
        ->tag('sylius.state_provider');
};
