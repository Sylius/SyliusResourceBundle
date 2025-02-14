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

use Doctrine\ORM\Mapping\Entity;
use Sylius\Resource\Metadata\Metadata;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterAliasesForDoctrineRepositoriesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('sylius.resources')) {
            return;
        }

        /** @var array $resources */
        $resources = $container->getParameter('sylius.resources');

        foreach ($resources as $resourceAlias => $configuration) {
            $model = $configuration['classes']['model'] ?? null;

            if (null === $model) {
                continue;
            }

            $reflection = new \ReflectionClass($model);
            $entityAttribute = $reflection->getAttributes(Entity::class)[0] ?? null;
            $repositoryClass = $entityAttribute?->getArguments()['repositoryClass'] ?? null;

            if (null === $repositoryClass) {
                continue;
            }

            $metadata = Metadata::fromAliasAndConfiguration($resourceAlias, $configuration);
            $repositoryAlias = $metadata->getServiceId('repository');

            if (!$container->hasDefinition($repositoryClass)) {
                continue;
            }

            $container->setAlias($repositoryAlias, $repositoryClass);
        }
    }
}
