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

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ContainerRepositoryFactory;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder\DefaultFormBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder\DefaultFormBuilder as DefaultFormBuilderInterface;
use Sylius\Bundle\ResourceBundle\EventListener\ORMMappedSuperClassSubscriber;
use Sylius\Bundle\ResourceBundle\EventListener\ORMRepositoryClassSubscriber;
use Sylius\Bundle\ResourceBundle\EventListener\ORMTranslatableListener as TranslatableListener;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('sylius.orm.repository.class', EntityRepository::class);
    $parameters->set('sylius.translation.translatable_listener.doctrine.orm.class', TranslatableListener::class);

    $services->defaults()
        ->public();

    $services->set('sylius.event_subscriber.orm_mapped_super_class', ORMMappedSuperClassSubscriber::class)
        ->args([service('sylius.resource_registry')])
        ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata', 'priority' => 8192]);

    $services->alias(ORMMappedSuperClassSubscriber::class, 'sylius.event_subscriber.orm_mapped_super_class');

    $services->set('sylius.event_subscriber.orm_repository_class', ORMRepositoryClassSubscriber::class)
        ->args([service('sylius.resource_registry')])
        ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata', 'priority' => 8192]);

    $services->alias(ORMRepositoryClassSubscriber::class, 'sylius.event_subscriber.orm_repository_class');

    $services->set('sylius.form_builder.default', DefaultFormBuilder::class)
        ->private()
        ->args([service('doctrine.orm.default_entity_manager')])
        ->tag('sylius.default_resource_form.builder', ['type' => 'doctrine/orm']);

    $services->alias(DefaultFormBuilderInterface::class, 'sylius.form_builder.default')
        ->private();

    $services->set('sylius.doctrine.orm.container_repository_factory', ContainerRepositoryFactory::class)
        ->private()
        ->decorate('doctrine.orm.container_repository_factory')
        ->args([
            service('sylius.doctrine.orm.container_repository_factory.inner'),
            '%sylius.doctrine.orm.container_repository_factory.entities%',
        ]);

    $services->alias(ContainerRepositoryFactory::class, 'sylius.doctrine.orm.container_repository_factory')
        ->private();
};
