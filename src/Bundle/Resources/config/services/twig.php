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

    $services->set('sylius.twig.context.factory', 'Sylius\Resource\Twig\Context\Factory\ContextFactory')
        ->args([tagged_locator('sylius.twig_context_factory')]);

    $services->set('sylius.twig.context.factory.default', 'Sylius\Resource\Twig\Context\Factory\DefaultContextFactory')
        ->tag('sylius.twig_context_factory');

    $services->alias('Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface', 'sylius.twig.context.factory.default');

    $services->set('sylius.twig.context.factory.request', 'Sylius\Resource\Twig\Context\Factory\RequestContextFactory')
        ->decorate('sylius.twig.context.factory')
        ->args([service('.inner')]);

    $services->set('sylius.twig.context.factory.legacy', 'Sylius\Bundle\ResourceBundle\Twig\Context\LegacyContextFactory')
        ->decorate('sylius.twig.context.factory')
        ->args([service('.inner')]);
};
