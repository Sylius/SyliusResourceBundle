<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use App\Service\FirstAutowiredService;
use App\Service\NoInterfaceAutowiredService;
use App\Service\SecondAutowiredService;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

if (method_exists($container, 'registerAliasForArgument')) {
    $container->autowire(FirstAutowiredService::class)->setPublic(true);
    $container->autowire(SecondAutowiredService::class)->setPublic(true);
    $container->autowire(NoInterfaceAutowiredService::class)->setPublic(true);
} else {
    $container->setDefinition(FirstAutowiredService::class, (new Definition(FirstAutowiredService::class, [
        new Reference('app.factory.book'),
        new Reference('app.repository.book'),
        new Reference('app.manager.book'),
    ]))->setPublic(true));

    $container->setDefinition(SecondAutowiredService::class, (new Definition(SecondAutowiredService::class, [
        new Reference('app.factory.book'),
        new Reference('app.repository.book'),
        new Reference('app.manager.book'),
    ]))->setPublic(true));

    $container->setDefinition(NoInterfaceAutowiredService::class, (new Definition(NoInterfaceAutowiredService::class, [
        new Reference('app.factory.book'),
        new Reference('app.repository.book'),
        new Reference('app.factory.comic_book'),
        new Reference('app.repository.comic_book'),
    ]))->setPublic(true));
}
