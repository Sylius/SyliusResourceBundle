<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * TODO Remove on sylius/resource-bundle 2.0
 */
final class CsrfTokenManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('security.csrf.token_manager')) {
            return;
        }

        $csrdTokenManagerDefinition = $container->getDefinition('security.csrf.token_manager');
        $csrdTokenManagerDefinition->setPublic(true);
    }
}
