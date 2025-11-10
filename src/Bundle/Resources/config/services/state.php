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

use Sylius\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Resource\State\Factory;
use Sylius\Resource\State\FactoryInterface;
use Sylius\Resource\State\Provider;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\State\Responder;
use Sylius\Resource\State\ResponderInterface;
use Sylius\Resource\Symfony\Request\State\ApiResponder;
use Sylius\Resource\Symfony\Request\State\TwigResponder;
use Sylius\Resource\Symfony\Response\ApiHeadersInitiator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $container->import('state/**/*.php');

    $services->set('sylius.resource_factory.expression_language', ExpressionLanguage::class);

    $services->set('sylius.state_provider.locator', Provider::class)
        ->args([tagged_locator('sylius.state_provider')]);

    $services->alias(ProviderInterface::class, 'sylius.state_provider');

    $services->set('sylius.state_factory', Factory::class)
        ->args([
            tagged_locator('sylius.resource_factory'),
            service('sylius.expression_language.argument_parser.factory'),
        ]);

    $services->alias(FactoryInterface::class, 'sylius.state_factory');

    $services->set('sylius.state_responder', Responder::class)
        ->args([tagged_locator('sylius.state_responder')]);

    $services->alias(ResponderInterface::class, 'sylius.state_responder');

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
            service(PersistProcessor::class)->nullOnInvalid(),
        ])
        ->tag('sylius.state_processor');

    $services->set('Sylius\Resource\Symfony\Request\State\Responder')
        ->args([tagged_locator('sylius.state_responder')])
        ->tag('sylius.state_responder');

    $services->set('sylius.state_responder.html', TwigResponder::class)
        ->args([
            service('sylius.routing.redirect_handler'),
            service('sylius.twig.context.factory'),
            service('twig')->nullOnInvalid(),
        ])
        ->tag('sylius.state_responder');

    $services->set('sylius.headers_initiator.api', ApiHeadersInitiator::class);

    $services->set('sylius.state_responder.api', ApiResponder::class)
        ->args([service('sylius.headers_initiator.api')])
        ->tag('sylius.state_responder');

    $services->set('Sylius\Resource\Grid\State\RequestGridProvider')
        ->args([
            service('sylius.grid.view_factory.resource')->nullOnInvalid(),
            service('sylius.grid.provider')->nullOnInvalid(),
        ])
        ->tag('sylius.state_provider');
};
