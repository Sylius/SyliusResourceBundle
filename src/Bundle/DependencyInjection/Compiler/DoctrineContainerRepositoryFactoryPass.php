<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrineORMDriver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Resolves given target entities with container parameters.
 * Usable only with *doctrine/orm* driver.
 */
final class DoctrineContainerRepositoryFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(DoctrineORMDriver::GENERIC_ENTITIES_PARAMETER)) {
            $container->setParameter(DoctrineORMDriver::GENERIC_ENTITIES_PARAMETER, []);
        }
    }
}
