<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApp_Controller_GedmoService extends App_KernelTestDebugContainer
{
    /**
     * Gets the public 'app.controller.gedmo' shared service.
     *
     * @return \Sylius\Bundle\ResourceBundle\Controller\ResourceController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ControllerTrait.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/dependency-injection/ContainerAwareTrait.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ResourceController.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Metadata/MetadataInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Metadata/Metadata.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Factory/FactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Component/Factory/Factory.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/NewResourceFactoryInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/NewResourceFactory.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/SingleResourceProviderInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/SingleResourceProvider.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/AuthorizationCheckerInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/DisabledAuthorizationChecker.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ResourceUpdateHandlerInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ResourceUpdateHandler.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ResourceDeleteHandlerInterface.php';
        include_once \dirname(__DIR__, 6).'/src/Bundle/Controller/ResourceDeleteHandler.php';

        $container->services['app.controller.gedmo'] = $instance = new \Sylius\Bundle\ResourceBundle\Controller\ResourceController(($container->privates['sylius.resource_registry'] ?? self::getSylius_ResourceRegistryService($container))->get('app.gedmo'), ($container->privates['sylius.resource_controller.request_configuration_factory'] ?? self::getSylius_ResourceController_RequestConfigurationFactoryService($container)), ($container->privates['sylius.resource_controller.view_handler'] ?? $container->load('getSylius_ResourceController_ViewHandlerService')), ($container->services['app.repository.gedmo'] ?? $container->load('getApp_Repository_GedmoService')), ($container->services['app.factory.gedmo'] ??= new \Sylius\Component\Resource\Factory\Factory('App\\Entity\\GedmoExtendedExample')), ($container->privates['sylius.resource_controller.new_resource_factory'] ??= new \Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory()), ($container->services['doctrine.orm.default_entity_manager'] ?? self::getDoctrine_Orm_DefaultEntityManagerService($container)), ($container->privates['sylius.resource_controller.single_resource_provider'] ??= new \Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider()), ($container->privates['sylius.resource_controller.resources_collection_provider'] ?? $container->load('getSylius_ResourceController_ResourcesCollectionProviderService')), ($container->privates['sylius.resource_controller.form_factory'] ?? $container->load('getSylius_ResourceController_FormFactoryService')), ($container->privates['sylius.resource_controller.redirect_handler'] ?? $container->load('getSylius_ResourceController_RedirectHandlerService')), ($container->privates['sylius.resource_controller.flash_helper'] ?? $container->load('getSylius_ResourceController_FlashHelperService')), ($container->privates['sylius.resource_controller.authorization_checker.disabled'] ??= new \Sylius\Bundle\ResourceBundle\Controller\DisabledAuthorizationChecker()), ($container->privates['sylius.resource_controller.event_dispatcher'] ?? $container->load('getSylius_ResourceController_EventDispatcherService')), NULL, ($container->privates['sylius.resource_controller.resource_update_handler'] ??= new \Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandler(NULL)), ($container->privates['sylius.resource_controller.resource_delete_handler'] ??= new \Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandler()));

        $instance->setContainer($container);

        return $instance;
    }
}
