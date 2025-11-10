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

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType as ResourceAutocompleteChoiceTypeInterface;
use Sylius\Resource\Symfony\Form\Factory\FormFactory;
use Sylius\Resource\Symfony\Form\Factory\FormFactoryInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius.form.type.resource_autocomplete_choice', ResourceAutocompleteChoiceType::class)
        ->args([service('sylius.registry.resource_repository')])
        ->tag('form.type');

    $services->alias(ResourceAutocompleteChoiceTypeInterface::class, 'sylius.form.type.resource_autocomplete_choice');

    $services->set('sylius.form.factory', FormFactory::class)
        ->private()
        ->args([service('form.factory')]);

    $services->alias(FormFactoryInterface::class, 'sylius.form.factory');
};
