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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * TODO Remove after bumping to > Symfony 5.x
 */
final class SessionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (method_exists(RequestStack::class, 'getSession')) {
            return;
        }

        if ($container->hasDefinition('sylius.resource_controller.flash_helper')) {
            $flashHelperDefinition = $container->getDefinition('sylius.resource_controller.flash_helper');
            $flashHelperDefinition->replaceArgument(0, new Reference('session'));
        }

        if ($container->hasDefinition('sylius.storage.session')) {
            $sessionStorageDefinition = $container->getDefinition('sylius.storage.session');
            $sessionStorageDefinition->replaceArgument(0, new Reference('session'));
        }
    }
}
