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

use Sylius\Bundle\ResourceBundle\Twig\Context\LegacyContextFactory;
use Sylius\Bundle\ResourceBundle\Twig\CsrfParameterExtension;
use Sylius\Resource\Twig\Context\Factory\ContextFactory;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Sylius\Resource\Twig\Context\Factory\DefaultContextFactory;
use Sylius\Resource\Twig\Context\Factory\RequestContextFactory;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.twig.extension.csrf_parameter', CsrfParameterExtension::class)
        ->args(['%sylius.resource.csrf_parameter%'])
        ->tag('twig.extension');

    $services->set('sylius.twig.context.factory', ContextFactory::class)
        ->args([tagged_locator('sylius.twig_context_factory')]);

    $services->set('sylius.twig.context.factory.default', DefaultContextFactory::class)
        ->tag('sylius.twig_context_factory');

    $services->alias(ContextFactoryInterface::class, 'sylius.twig.context.factory.default');

    $services->set('sylius.twig.context.factory.request', RequestContextFactory::class)
        ->decorate('sylius.twig.context.factory')
        ->args([service('.inner')]);

    $services->set('sylius.twig.context.factory.legacy', LegacyContextFactory::class)
        ->decorate('sylius.twig.context.factory')
        ->args([service('.inner')]);
};
