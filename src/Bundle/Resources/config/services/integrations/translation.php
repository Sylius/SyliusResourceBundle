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

use Sylius\Bundle\ResourceBundle\EventListener\ORMTranslatableListener;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType as ResourceTranslationsTypeInterface;
use Sylius\Component\Resource\Translation\Provider\ImmutableTranslationLocaleProvider as ComponentImmutableTranslationLocaleProvider;
use Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssigner;
use Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssignerInterface;
use Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider as ResourceImmutableTranslationLocaleProvider;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius.translation_locale_provider.immutable', ResourceImmutableTranslationLocaleProvider::class)
        ->args([
            ['' => '%locale%'],
            '%locale%',
        ]);

    $services->alias(ComponentImmutableTranslationLocaleProvider::class, 'sylius.translation_locale_provider.immutable')
        ->deprecate('sylius/resource-bundle', '1.11', 'The "%alias_id%" service alias is deprecated since sylius/resource-bundle 1.11 and will be removed in sylius/resource-bundle 2.0. Use Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider instead.');

    $services->alias(ResourceImmutableTranslationLocaleProvider::class, 'sylius.translation_locale_provider.immutable');

    $services->alias(TranslationLocaleProviderInterface::class, 'sylius.translation_locale_provider.immutable');

    $services->set('sylius.translation.translatable_listener.doctrine.orm', ORMTranslatableListener::class)
        ->args([
            service('sylius.resource_registry'),
            service('sylius.translatable_entity_locale_assigner'),
        ])
        ->tag('doctrine.event_listener', ['connection' => 'default', 'event' => 'loadClassMetadata', 'priority' => 99])
        ->tag('doctrine.event_listener', ['connection' => 'default', 'event' => 'postLoad', 'priority' => 99]);

    $services->alias(ORMTranslatableListener::class, 'sylius.translation.translatable_listener.doctrine.orm');

    $services->set('sylius.form.type.resource_translations', ResourceTranslationsType::class)
        ->args([service('sylius.translation_locale_provider')])
        ->tag('form.type');

    $services->alias(ResourceTranslationsTypeInterface::class, 'sylius.form.type.resource_translations');

    $services->set('sylius.translatable_entity_locale_assigner', TranslatableEntityLocaleAssigner::class)
        ->args([service('sylius.translation_locale_provider')]);

    $services->alias(TranslatableEntityLocaleAssignerInterface::class, 'sylius.translatable_entity_locale_assigner');
};
