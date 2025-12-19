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

use Hateoas\Representation\Factory\PagerfantaFactory;
use Sylius\Bundle\ResourceBundle\Controller\DisabledAuthorizationChecker;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcher;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelper;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParser;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandler;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandler;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactory;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProvider;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolver;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandler;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandler;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Resource\Symfony\Controller\MainController;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.main_controller', MainController::class)
        ->args([
            service('sylius.resource_metadata_operation.initiator.http_operation'),
            service('sylius.context.initiator.request_context'),
            service('sylius.state_provider.main'),
            service('sylius.state_processor.main'),
        ])
        ->tag('controller.service_arguments');

    $services->set('sylius.resource_controller.parameters_parser', ParametersParser::class)
        ->args([
            service('service_container'),
            service('sylius.expression_language'),
        ]);

    $services->alias(ParametersParserInterface::class, 'sylius.resource_controller.parameters_parser');

    $services->set('sylius.resource_controller.request_configuration_factory', RequestConfigurationFactory::class)
        ->args([
            service('sylius.resource_controller.parameters_parser'),
            RequestConfiguration::class,
            '%sylius.resource.settings%',
        ]);

    $services->alias(RequestConfigurationFactoryInterface::class, 'sylius.resource_controller.request_configuration_factory');

    $services->set('sylius.resource_controller.new_resource_factory', NewResourceFactory::class);

    $services->alias(NewResourceFactoryInterface::class, 'sylius.resource_controller.new_resource_factory');

    $services->set('sylius.resource_controller.single_resource_provider', SingleResourceProvider::class);

    $services->alias(SingleResourceProviderInterface::class, 'sylius.resource_controller.single_resource_provider');

    $services->set('sylius.resource_controller.pagerfanta_representation_factory', PagerfantaFactory::class);

    $services->alias(PagerfantaFactory::class, 'sylius.resource_controller.pagerfanta_representation_factory');

    $services->set('sylius.resource_controller.resources_resolver', ResourcesResolver::class);

    $services->alias(ResourcesResolverInterface::class, 'sylius.resource_controller.resources_resolver');

    $services->set('sylius.resource_controller.resources_collection_provider', ResourcesCollectionProvider::class)
        ->args([
            service('sylius.resource_controller.resources_resolver'),
            service('sylius.resource_controller.pagerfanta_representation_factory')->nullOnInvalid(),
        ]);

    $services->alias(ResourcesCollectionProviderInterface::class, 'sylius.resource_controller.resources_collection_provider');

    $services->set('sylius.resource_controller.form_factory', ResourceFormFactory::class)
        ->args([service('form.factory')]);

    $services->alias(ResourceFormFactoryInterface::class, 'sylius.resource_controller.form_factory');

    $services->set('sylius.resource_controller.redirect_handler', RedirectHandler::class)
        ->args([service('router')]);

    $services->alias(RedirectHandlerInterface::class, 'sylius.resource_controller.redirect_handler');

    $services->set('sylius.resource_controller.authorization_checker.disabled', DisabledAuthorizationChecker::class);

    $services->alias(DisabledAuthorizationChecker::class, 'sylius.resource_controller.authorization_checker.disabled');

    $services->set('sylius.resource_controller.flash_helper', FlashHelper::class)
        ->args([
            service('request_stack'),
            service('translator'),
            '%locale%',
        ]);

    $services->alias(FlashHelperInterface::class, 'sylius.resource_controller.flash_helper');

    $services->set('sylius.resource_controller.event_dispatcher', EventDispatcher::class)
        ->args([service('event_dispatcher')]);

    $services->alias(EventDispatcherInterface::class, 'sylius.resource_controller.event_dispatcher');

    $services->set('sylius.resource_controller.view_handler', ViewHandler::class)
        ->args([service('fos_rest.view_handler')->nullOnInvalid()]);

    $services->alias(ViewHandlerInterface::class, 'sylius.resource_controller.view_handler');

    $services->set('sylius.resource_controller.resource_update_handler', ResourceUpdateHandler::class)
        ->args([service('sylius.resource_controller.state_machine')->nullOnInvalid()]);

    $services->alias(ResourceUpdateHandlerInterface::class, 'sylius.resource_controller.resource_update_handler');

    $services->set('sylius.resource_controller.resource_delete_handler', ResourceDeleteHandler::class);

    $services->alias(ResourceDeleteHandlerInterface::class, 'sylius.resource_controller.resource_delete_handler');
};
