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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver;

use Sylius\Bundle\ResourceBundle\Controller\Action\ApplyStateMachineTransitionAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\BulkDeleteAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\CreateAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\DeleteAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\IndexAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\ShowAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\UpdateAction;
use Sylius\Bundle\ResourceBundle\Controller\TemplateRenderer;
use Sylius\Bundle\ResourceBundle\Controller\TemplateRendererInterface;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\TranslatableFactoryInterface;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

abstract class AbstractDriver implements DriverInterface
{
    public function load(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $this->setClassesParameters($container, $metadata);

        if ($metadata->hasClass('controller')) {
            $this->addController($container, $metadata);
        }

        $this->addManager($container, $metadata);
        $this->addRepository($container, $metadata);

        if ($metadata->hasClass('factory')) {
            $this->addFactory($container, $metadata);
        }
    }

    protected function setClassesParameters(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        if ($metadata->hasClass('model')) {
            $container->setParameter(sprintf('%s.model.%s.class', $metadata->getApplicationName(), $metadata->getName()), $metadata->getClass('model'));
        }
        if ($metadata->hasClass('controller')) {
            $container->setParameter(sprintf('%s.controller.%s.class', $metadata->getApplicationName(), $metadata->getName()), $metadata->getClass('controller'));
        }
        if ($metadata->hasClass('factory')) {
            $container->setParameter(sprintf('%s.factory.%s.class', $metadata->getApplicationName(), $metadata->getName()), $metadata->getClass('factory'));
        }
        if ($metadata->hasClass('repository')) {
            $container->setParameter(sprintf('%s.repository.%s.class', $metadata->getApplicationName(), $metadata->getName()), $metadata->getClass('repository'));
        }
    }

    protected function addController(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $this->registerTemplateRenderer($container);
        $this->registerActionServices($container, $metadata);

        $definition = new Definition($metadata->getClass('controller'));
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference('sylius.resource_controller.view_handler', ContainerInterface::NULL_ON_INVALID_REFERENCE),
                new Reference($metadata->getServiceId('repository')),
                new Reference($metadata->getServiceId('factory')),
                new Reference('sylius.resource_controller.new_resource_factory'),
                new Reference($metadata->getServiceId('manager')),
                new Reference('sylius.resource_controller.single_resource_provider'),
                new Reference('sylius.resource_controller.resources_collection_provider'),
                new Reference('sylius.resource_controller.form_factory'),
                new Reference('sylius.resource_controller.redirect_handler'),
                new Reference('sylius.resource_controller.flash_helper'),
                new Reference('sylius.resource_controller.authorization_checker'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference('sylius.resource_controller.state_machine', ContainerInterface::NULL_ON_INVALID_REFERENCE),
                new Reference('sylius.resource_controller.resource_update_handler'),
                new Reference('sylius.resource_controller.resource_delete_handler'),
            ])
            ->addMethodCall('setContainer', [new Reference('service_container')])
            ->addTag('controller.service_arguments')
        ;

        $container->setDefinition($metadata->getServiceId('controller'), $definition);
    }

    protected function addFactory(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $factoryClass = $metadata->getClass('factory');
        $modelClass = $metadata->getClass('model');

        $definition = new Definition($factoryClass);
        $definition->setPublic(true);

        $definitionArgs = [$modelClass];

        /** @var array $factoryInterfaces */
        $factoryInterfaces = class_implements($factoryClass);
        if (in_array(TranslatableFactoryInterface::class, $factoryInterfaces, true)) {
            $decoratedDefinition = new Definition(Factory::class);
            $decoratedDefinition->setArguments($definitionArgs);

            $definitionArgs = [$decoratedDefinition, new Reference('sylius.translation_locale_provider')];
        }

        $definition->setArguments($definitionArgs);

        $container->setDefinition($metadata->getServiceId('factory'), $definition);

        /** @var array $factoryParents */
        $factoryParents = class_parents($factoryClass);

        $typehintClasses = array_merge(
            $factoryInterfaces,
            [$factoryClass],
            $factoryParents
        );

        foreach ($typehintClasses as $typehintClass) {
            $container->registerAliasForArgument(
                $metadata->getServiceId('factory'),
                $typehintClass,
                $metadata->getHumanizedName() . ' factory'
            );
        }
    }

    protected function getMetadataDefinition(MetadataInterface $metadata): Definition
    {
        $definition = new Definition(Metadata::class);
        $definition
            ->setFactory([new Reference('sylius.resource_registry'), 'get'])
            ->setArguments([$metadata->getAlias()])
        ;

        return $definition;
    }

    protected function registerTemplateRenderer(ContainerBuilder $container): void
    {
        $definition = new Definition(TemplateRendererInterface::class);
        $definition
            ->setClass(TemplateRenderer::class)
            ->setArguments([
                new Reference('twig', ContainerInterface::NULL_ON_INVALID_REFERENCE),
                new Reference('templating', ContainerInterface::NULL_ON_INVALID_REFERENCE),
            ])
        ;

        $container->setDefinition('sylius.resource_controller.template_renderer', $definition);
    }

    protected function registerActionServices(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $definition = new Definition(ShowAction::class);
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference($metadata->getServiceId('repository')),
                new Reference('sylius.finder.single_resource'),
                new Reference('sylius.resource_controller.template_renderer'),
                new Reference('sylius.checker.request_permission'),
                new Reference('sylius.creator.rest_view'),
            ])
        ;

        $container->setDefinition($metadata->getServiceId('action.show'), $definition);

        $definition = new Definition(IndexAction::class);
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference($metadata->getServiceId('repository')),
                new Reference('sylius.resource_controller.resources_collection_provider'),
                new Reference('sylius.resource_controller.template_renderer'),
                new Reference('sylius.checker.request_permission'),
                new Reference('sylius.creator.rest_view'),
            ])
        ;

        $container->setDefinition($metadata->getServiceId('action.index'), $definition);

        $definition = new Definition(CreateAction::class);
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference($metadata->getServiceId('repository')),
                new Reference($metadata->getServiceId('factory')),
                new Reference('sylius.resource_controller.new_resource_factory'),
                new Reference('sylius.resource_controller.form_factory'),
                new Reference('sylius.resource_controller.redirect_handler'),
                new Reference('sylius.resource_controller.flash_helper'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference('sylius.resource_controller.template_renderer'),
                new Reference('sylius.checker.request_permission'),
                new Reference('sylius.provider.state_machine'),
                new Reference('sylius.creator.rest_view'),
            ])
        ;

        $container->setDefinition($metadata->getServiceId('action.create'), $definition);

        $definition = new Definition(UpdateAction::class);
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference($metadata->getServiceId('repository')),
                new Reference($metadata->getServiceId('manager')),
                new Reference('sylius.finder.single_resource'),
                new Reference('sylius.resource_controller.form_factory'),
                new Reference('sylius.resource_controller.redirect_handler'),
                new Reference('sylius.resource_controller.flash_helper'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference('sylius.resource_controller.resource_update_handler'),
                new Reference('sylius.resource_controller.template_renderer'),
                new Reference('sylius.checker.request_permission'),
                new Reference('sylius.creator.rest_view'),
            ])
        ;

        $container->setDefinition($metadata->getServiceId('action.update'), $definition);

        $definition = new Definition(DeleteAction::class);
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference($metadata->getServiceId('repository')),
                new Reference('sylius.finder.single_resource'),
                new Reference('sylius.resource_controller.redirect_handler'),
                new Reference('sylius.resource_controller.flash_helper'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference('sylius.resource_controller.resource_delete_handler'),
                new Reference('security.csrf.token_manager'),
                new Reference('sylius.checker.request_permission'),
                new Reference('sylius.creator.rest_view'),
            ])
        ;

        $container->setDefinition($metadata->getServiceId('action.delete'), $definition);

        $definition = new Definition(BulkDeleteAction::class);
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference('sylius.resource_controller.resources_collection_provider'),
                new Reference($metadata->getServiceId('repository')),
                new Reference('sylius.resource_controller.redirect_handler'),
                new Reference('sylius.resource_controller.flash_helper'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference('sylius.resource_controller.resource_delete_handler'),
                new Reference('security.csrf.token_manager'),
                new Reference('sylius.checker.request_permission'),
                new Reference('sylius.creator.rest_view'),
            ])
        ;

        $container->setDefinition($metadata->getServiceId('action.bulk_delete'), $definition);

        $definition = new Definition(ApplyStateMachineTransitionAction::class);
        $definition
            ->setPublic(true)
            ->setArguments([
                $this->getMetadataDefinition($metadata),
                new Reference('sylius.resource_controller.request_configuration_factory'),
                new Reference($metadata->getServiceId('repository')),
                new Reference($metadata->getServiceId('manager')),
                new Reference('sylius.finder.single_resource'),
                new Reference('sylius.resource_controller.redirect_handler'),
                new Reference('sylius.resource_controller.flash_helper'),
                new Reference('sylius.resource_controller.event_dispatcher'),
                new Reference('sylius.resource_controller.resource_update_handler'),
                new Reference('security.csrf.token_manager'),
                new Reference('sylius.checker.request_permission'),
                new Reference('sylius.provider.state_machine'),
                new Reference('sylius.creator.rest_view'),
            ])
        ;

        $container->setDefinition($metadata->getServiceId('action.apply_state_machine_transition'), $definition);
    }

    abstract protected function addManager(ContainerBuilder $container, MetadataInterface $metadata): void;

    abstract protected function addRepository(ContainerBuilder $container, MetadataInterface $metadata): void;
}
