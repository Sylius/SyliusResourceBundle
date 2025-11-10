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

    $services->set('sylius.form.type.resource_autocomplete_choice', 'Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType')
        ->args([service('sylius.registry.resource_repository')])
        ->tag('form.type');

    $services->alias('Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType', 'sylius.form.type.resource_autocomplete_choice');

    $services->set('sylius.form.factory', 'Sylius\Resource\Symfony\Form\Factory\FormFactory')
        ->private()
        ->args([service('form.factory')]);

    $services->alias('Sylius\Resource\Symfony\Form\Factory\FormFactoryInterface', 'sylius.form.factory');
};
