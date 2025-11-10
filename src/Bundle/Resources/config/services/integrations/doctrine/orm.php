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
    $parameters->set('sylius.orm.repository.class', 'Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository');
    $parameters->set('sylius.translation.translatable_listener.doctrine.orm.class', 'Sylius\Bundle\ResourceBundle\EventListener\ORMTranslatableListener');

    $services->defaults()
        ->public();

    $services->set('sylius.event_subscriber.orm_mapped_super_class', 'Sylius\Bundle\ResourceBundle\EventListener\ORMMappedSuperClassSubscriber')
        ->args([service('sylius.resource_registry')])
        ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata', 'priority' => 8192]);

    $services->alias('Sylius\Bundle\ResourceBundle\EventListener\ORMMappedSuperClassSubscriber', 'sylius.event_subscriber.orm_mapped_super_class');

    $services->set('sylius.event_subscriber.orm_repository_class', 'Sylius\Bundle\ResourceBundle\EventListener\ORMRepositoryClassSubscriber')
        ->args([service('sylius.resource_registry')])
        ->tag('doctrine.event_listener', ['event' => 'loadClassMetadata', 'priority' => 8192]);

    $services->alias('Sylius\Bundle\ResourceBundle\EventListener\ORMRepositoryClassSubscriber', 'sylius.event_subscriber.orm_repository_class');

    $services->set('sylius.form_builder.default', 'Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder\DefaultFormBuilder')
        ->private()
        ->args([service('doctrine.orm.default_entity_manager')])
        ->tag('sylius.default_resource_form.builder', ['type' => 'doctrine/orm']);

    $services->alias('Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder\DefaultFormBuilder', 'sylius.form_builder.default')
        ->private();

    $services->set('sylius.doctrine.orm.container_repository_factory', 'Sylius\Bundle\ResourceBundle\Doctrine\ORM\ContainerRepositoryFactory')
        ->private()
        ->decorate('doctrine.orm.container_repository_factory')
        ->args([
            service('sylius.doctrine.orm.container_repository_factory.inner'),
            '%sylius.doctrine.orm.container_repository_factory.entities%',
        ]);

    $services->alias('Sylius\Bundle\ResourceBundle\Doctrine\ORM\ContainerRepositoryFactory', 'sylius.doctrine.orm.container_repository_factory')
        ->private();
};
