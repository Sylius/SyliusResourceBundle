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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterResourceRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('sylius.resources') || !$container->has('sylius.registry.resource_repository')) {
            return;
        }

        /** @var array $resources */
        $resources = $container->getParameter('sylius.resources');

        $repositoryRegistry = $container->findDefinition('sylius.registry.resource_repository');

        foreach ($resources as $alias => $configuration) {
            [$applicationName, $resourceName] = explode('.', $alias, 2);
            $repositoryId = sprintf('%s.repository.%s', $applicationName, $resourceName);

            if ($container->has($repositoryId)) {
                $repositoryDefinition = $container->findDefinition($repositoryId);

                // Do not register repositories that do not implement the Sylius repository interface
                if (!\is_a($repositoryDefinition->getClass() ?? '', RepositoryInterface::class, true)) {
                    continue;
                }

                $repositoryRegistry->addMethodCall('register', [$alias, new Reference($repositoryId)]);
            }
        }
    }
}
