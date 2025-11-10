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

    $services->set('sylius.translation_locale_provider.immutable', 'Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider')
        ->args([
            ['' => '%locale%'],
            '%locale%',
        ]);

    $services->alias('Sylius\Component\Resource\Translation\Provider\ImmutableTranslationLocaleProvider', 'sylius.translation_locale_provider.immutable')
        ->deprecate('sylius/resource-bundle', '1.11', 'The "%alias_id%" service alias is deprecated since sylius/resource-bundle 1.11 and will be removed in sylius/resource-bundle 2.0. Use Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider instead.');

    $services->alias('Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider', 'sylius.translation_locale_provider.immutable');

    $services->alias('Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface', 'sylius.translation_locale_provider.immutable');

    $services->set('sylius.translation.translatable_listener.doctrine.orm', 'Sylius\Bundle\ResourceBundle\EventListener\ORMTranslatableListener')
        ->args([
            service('sylius.resource_registry'),
            service('sylius.translatable_entity_locale_assigner'),
        ])
        ->tag('doctrine.event_listener', ['connection' => 'default', 'event' => 'loadClassMetadata', 'priority' => 99])
        ->tag('doctrine.event_listener', ['connection' => 'default', 'event' => 'postLoad', 'priority' => 99]);

    $services->alias('Sylius\Bundle\ResourceBundle\EventListener\ORMTranslatableListener', 'sylius.translation.translatable_listener.doctrine.orm');

    $services->set('sylius.form.type.resource_translations', 'Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType')
        ->args([service('sylius.translation_locale_provider')])
        ->tag('form.type');

    $services->alias('Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType', 'sylius.form.type.resource_translations');

    $services->set('sylius.translatable_entity_locale_assigner', 'Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssigner')
        ->args([service('sylius.translation_locale_provider')]);

    $services->alias('Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssignerInterface', 'sylius.translatable_entity_locale_assigner');
};
