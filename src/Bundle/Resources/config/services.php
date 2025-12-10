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

use Sylius\Bundle\ResourceBundle\ExpressionLanguage\ExpressionLanguage as BundleExpressionLanguage;
use Sylius\Bundle\ResourceBundle\ExpressionLanguage\ExpressionLanguage as BundleExpressionLanguageInterface;
use Sylius\Bundle\ResourceBundle\Form\Extension\CollectionTypeExtension;
use Sylius\Bundle\ResourceBundle\Form\Extension\CollectionTypeExtension as CollectionTypeExtensionInterface;
use Sylius\Bundle\ResourceBundle\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType as DefaultResourceTypeInterface;
use Sylius\Component\Registry\ServiceRegistry;
use Sylius\Resource\Generator\RandomnessGenerator;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;
use Sylius\Resource\Metadata\Registry;
use Sylius\Resource\Metadata\RegistryInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $container->import('services/console.php');
    $container->import('services/context.php');
    $container->import('services/controller.php');
    $container->import('services/dispatcher.php');
    $container->import('services/expression_language.php');
    $container->import('services/form.php');
    $container->import('services/helper.php');
    $container->import('services/listener.php');
    $container->import('services/metadata.php');
    $container->import('services/routing.php');
    $container->import('services/security.php');
    $container->import('services/state.php');
    $container->import('services/state_machine.php');
    $container->import('services/storage.php');
    $container->import('services/twig.php');

    $parameters->set('sylius.state_machine.class', 'Sylius\Resource\StateMachine\StateMachine');

    $services->defaults()
        ->public();

    $services->set('sylius.random_generator', RandomnessGenerator::class);

    $services->alias(RandomnessGeneratorInterface::class, 'sylius.random_generator');

    $services->alias(\Sylius\Component\Resource\Generator\RandomnessGeneratorInterface::class, 'sylius.random_generator')
        ->deprecate('sylius/resource-bundle', '1.11', 'The "%alias_id%" service alias is deprecated since sylius/resource-bundle 1.11 and will be removed in sylius/resource-bundle 2.0. Use Sylius\Resource\Generator\RandomnessGeneratorInterface instead.');

    $services->set('sylius.form.type_extension.form.request_handler', HttpFoundationRequestHandler::class)
        ->private()
        ->decorate('form.type_extension.form.request_handler', null, 256);

    $services->set('sylius.resource_registry', Registry::class)
        ->private();

    $services->alias(RegistryInterface::class, 'sylius.resource_registry')
        ->private();

    $services->alias(\Sylius\Component\Resource\Metadata\RegistryInterface::class, 'sylius.resource_registry')
        ->private()
        ->deprecate('sylius/resource-bundle', '1.11', 'The "%alias_id%" service alias is deprecated since sylius/resource-bundle 1.11 and will be removed in sylius/resource-bundle 2.0. Use Sylius\Resource\Metadata\RegistryInterface instead.');

    $services->set('sylius.expression_language', BundleExpressionLanguage::class)
        ->private();

    $services->alias(BundleExpressionLanguageInterface::class, 'sylius.expression_language')
        ->private();

    $services->set('sylius.form.extension.type.collection', CollectionTypeExtension::class)
        ->tag('form.type_extension', ['extended_type' => 'Symfony\Component\Form\Extension\Core\Type\CollectionType']);

    $services->alias(CollectionTypeExtensionInterface::class, 'sylius.form.extension.type.collection');

    $services->set('sylius.form.type.default', DefaultResourceType::class)
        ->args([
            service('sylius.resource_registry'),
            service('sylius.registry.form_builder'),
        ])
        ->tag('form.type');

    $services->alias(DefaultResourceTypeInterface::class, 'sylius.form.type.default');

    $services->set('sylius.registry.resource_repository', ServiceRegistry::class)
        ->private()
        ->args([
            'Doctrine\Persistence\ObjectRepository',
            'resource repository',
        ]);

    $services->set('sylius.registry.form_builder', ServiceRegistry::class)
        ->private()
        ->args([
            'Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface',
            'form builder',
        ]);
};
