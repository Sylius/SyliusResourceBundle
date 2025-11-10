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

    $services->defaults()
        ->public();

    $services->set('sylius.main_controller', 'Sylius\Resource\Symfony\Controller\MainController')
        ->private()
        ->args([
            service('sylius.resource_metadata_operation.initiator.http_operation'),
            service('sylius.context.initiator.request_context'),
            service('sylius.state_provider.main'),
            service('sylius.state_processor.main'),
        ])
        ->tag('controller.service_arguments');

    $services->set('sylius.resource_controller.parameters_parser', 'Sylius\Bundle\ResourceBundle\Controller\ParametersParser')
        ->private()
        ->args([
            service('service_container'),
            service('sylius.expression_language'),
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface', 'sylius.resource_controller.parameters_parser')
        ->private();

    $services->set('sylius.resource_controller.request_configuration_factory', 'Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory')
        ->private()
        ->args([
            service('sylius.resource_controller.parameters_parser'),
            'Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration',
            '%sylius.resource.settings%',
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface', 'sylius.resource_controller.request_configuration_factory')
        ->private();

    $services->set('sylius.resource_controller.new_resource_factory', 'Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory')
        ->private();

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface', 'sylius.resource_controller.new_resource_factory')
        ->private();

    $services->set('sylius.resource_controller.single_resource_provider', 'Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider')
        ->private();

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface', 'sylius.resource_controller.single_resource_provider')
        ->private();

    $services->set('sylius.resource_controller.pagerfanta_representation_factory', 'Hateoas\Representation\Factory\PagerfantaFactory')
        ->private();

    $services->alias('Hateoas\Representation\Factory\PagerfantaFactory', 'sylius.resource_controller.pagerfanta_representation_factory')
        ->private();

    $services->set('sylius.resource_controller.resources_resolver', 'Sylius\Bundle\ResourceBundle\Controller\ResourcesResolver')
        ->private();

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface', 'sylius.resource_controller.resources_resolver')
        ->private();

    $services->set('sylius.resource_controller.resources_collection_provider', 'Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProvider')
        ->private()
        ->args([
            service('sylius.resource_controller.resources_resolver'),
            service('sylius.resource_controller.pagerfanta_representation_factory')->nullOnInvalid(),
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface', 'sylius.resource_controller.resources_collection_provider')
        ->private();

    $services->set('sylius.resource_controller.form_factory', 'Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactory')
        ->private()
        ->args([service('form.factory')]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface', 'sylius.resource_controller.form_factory')
        ->private();

    $services->set('sylius.resource_controller.redirect_handler', 'Sylius\Bundle\ResourceBundle\Controller\RedirectHandler')
        ->private()
        ->args([service('router')]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface', 'sylius.resource_controller.redirect_handler')
        ->private();

    $services->set('sylius.resource_controller.authorization_checker.disabled', 'Sylius\Bundle\ResourceBundle\Controller\DisabledAuthorizationChecker')
        ->private();

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\DisabledAuthorizationChecker', 'sylius.resource_controller.authorization_checker.disabled')
        ->private();

    $services->set('sylius.resource_controller.flash_helper', 'Sylius\Bundle\ResourceBundle\Controller\FlashHelper')
        ->private()
        ->args([
            service('request_stack'),
            service('translator'),
            '%locale%',
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface', 'sylius.resource_controller.flash_helper')
        ->private();

    $services->set('sylius.resource_controller.event_dispatcher', 'Sylius\Bundle\ResourceBundle\Controller\EventDispatcher')
        ->private()
        ->args([service('event_dispatcher')]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface', 'sylius.resource_controller.event_dispatcher')
        ->private();

    $services->set('sylius.resource_controller.view_handler', 'Sylius\Bundle\ResourceBundle\Controller\ViewHandler')
        ->private()
        ->args([service('fos_rest.view_handler')]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface', 'sylius.resource_controller.view_handler')
        ->private();

    $services->set('sylius.resource_controller.resource_update_handler', 'Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandler')
        ->private()
        ->args([service('sylius.resource_controller.state_machine')->nullOnInvalid()]);

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface', 'sylius.resource_controller.resource_update_handler')
        ->private();

    $services->set('sylius.resource_controller.resource_delete_handler', 'Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandler')
        ->private();

    $services->alias('Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface', 'sylius.resource_controller.resource_delete_handler')
        ->private();
};
