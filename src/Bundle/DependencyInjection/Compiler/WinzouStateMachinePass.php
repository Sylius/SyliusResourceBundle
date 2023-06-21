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

use SM\Callback\CallbackFactoryInterface;
use SM\Callback\CascadeTransitionCallback;
use SM\Factory\FactoryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

/**
 * Marks WinzouStateMachineBundle's services as public for compatibility with both Symfony 3.4 and 4.0+.
 * Aliases FQCN-based services for backwards compatibility of Winzou/StateMachineBundle 0.4 with 0.3
 *
 * @see https://github.com/winzou/StateMachineBundle/pull/44
 * @see https://github.com/winzou/StateMachineBundle/commit/f515c9302783ef2575570d33b20aefa1eb265afb
 */
final class WinzouStateMachinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array $bundles */
        $bundles = $container->getParameter('kernel.bundles');
        $winzouStateMachineEnabled = in_array(winzouStateMachineBundle::class, $bundles, true);

        if (!$winzouStateMachineEnabled) {
            return;
        }

        if ($container->hasDefinition('sm.factory') && !$container->hasDefinition(FactoryInterface::class)) {
            $container->setAlias(FactoryInterface::class, 'sm.factory');
        } else {
            $container->setAlias('sm.factory', FactoryInterface::class);
        }

        if ($container->hasDefinition('sm.callback_factory') && !$container->hasDefinition(CallbackFactoryInterface::class)) {
            $container->setAlias(CallbackFactoryInterface::class, 'sm.callback_factory');
        } else {
            $container->setAlias('sm.callback_factory', CallbackFactoryInterface::class);
        }

        if ($container->hasDefinition('sm.callback.cascade_transition') && !$container->hasDefinition(CascadeTransitionCallback::class)) {
            $container->setAlias(CascadeTransitionCallback::class, 'sm.callback.cascade_transition');
        } else {
            $container->setAlias('sm.callback.cascade_transition', CascadeTransitionCallback::class);
        }

        $services = [
            'sm.factory',
            'sm.callback_factory',
            'sm.callback.cascade_transition',
            FactoryInterface::class,
            CallbackFactoryInterface::class,
            CascadeTransitionCallback::class,
        ];

        foreach ($services as $id) {
            if ($container->hasAlias($id)) {
                $container->getAlias($id)->setPublic(true);
            }

            if ($container->hasDefinition($id)) {
                $container->getDefinition($id)->setPublic(true);
            }
        }
    }
}
