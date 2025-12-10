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

use Sylius\Resource\State\Provider\FactoryProvider;
use Sylius\Resource\State\Provider\ReadProvider;
use Sylius\Resource\State\Provider\SecurityProvider;
use Sylius\Resource\Symfony\EventDispatcher\State\DispatchPostReadEventProvider;
use Sylius\Resource\Symfony\Form\State\FormProvider;
use Sylius\Resource\Symfony\Serializer\State\DeserializeProvider;
use Sylius\Resource\Symfony\Validator\State\ValidateProvider;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.state_provider.read', ReadProvider::class)
        ->decorate('sylius.state_provider.locator')
        ->args([service('.inner')]);

    $services->alias('sylius.state_provider.main', 'sylius.state_provider.read');

    $services->set('sylius.state_provider.factory', FactoryProvider::class)
        ->decorate('sylius.state_provider.read', null, 500)
        ->args([
            service('.inner'),
            service('sylius.state_factory'),
        ]);

    $services->set('sylius.state_provider.dispatch_post_read_event', DispatchPostReadEventProvider::class)
        ->decorate('sylius.state_provider.read', null, 400)
        ->args([
            service('.inner'),
            service('sylius.dispatcher.operation'),
        ]);

    $services->set('sylius.state_provider.deserialize', DeserializeProvider::class)
        ->decorate('sylius.state_provider.read', null, 300)
        ->args([
            service('.inner'),
            service('serializer')->nullOnInvalid(),
        ]);

    $services->set('sylius.state_provider.form', FormProvider::class)
        ->decorate('sylius.state_provider.read', null, 200)
        ->args([
            service('.inner'),
            service('sylius.form.factory'),
        ]);

    $services->set('sylius.state_provider.validate', ValidateProvider::class)
        ->decorate('sylius.state_provider.read', null, 100)
        ->args([
            service('.inner'),
            service('validator'),
        ]);

    $services->set('sylius.state_provider.security', SecurityProvider::class)
        ->decorate('sylius.state_provider.read', null, -100)
        ->args([
            service('.inner'),
            service('sylius.security.operation_access_checker'),
        ]);
};
