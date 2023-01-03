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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\ServiceRepositoryCompilerPass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ObjectManager as DeprecatedObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class DoctrineORMDriver extends AbstractDoctrineDriver
{
    public const GENERIC_ENTITIES_PARAMETER = 'sylius.doctrine.orm.container_repository_factory.entities';

    public function getType(): string
    {
        return SyliusResourceBundle::DRIVER_DOCTRINE_ORM;
    }

    protected function addRepository(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $repositoryClassParameterName = sprintf('%s.repository.%s.class', $metadata->getApplicationName(), $metadata->getName());
        $repositoryClass = EntityRepository::class;

        /** @var string[] $genericEntities */
        $genericEntities = $container->hasParameter(self::GENERIC_ENTITIES_PARAMETER) ? $container->getParameter(self::GENERIC_ENTITIES_PARAMETER) : [];

        if ($container->hasParameter($repositoryClassParameterName)) {
            /** @var string $repositoryClass */
            $repositoryClass = $container->getParameter($repositoryClassParameterName);
        }

        if ($metadata->hasClass('repository')) {
            /** @var string $repositoryClass */
            $repositoryClass = $metadata->getClass('repository');
        }

        $serviceId = $metadata->getServiceId('repository');
        $managerReference = new Reference($metadata->getServiceId('manager'));
        $definition = new Definition($repositoryClass);
        $definition->setPublic(true);
        if ($repositoryClass === EntityRepository::class) {
            /** @var string $entityClass */
            $entityClass = $metadata->getClass('model');

            $definition->setFactory([$managerReference, 'getRepository']);
            $definition->setArguments([$entityClass]);

            $container->setDefinition($serviceId, $definition);

            $genericEntities[] = $entityClass;
        } else {
            if (is_a($repositoryClass, ServiceEntityRepository::class, true)) {
                $definition->setArguments([new Reference('doctrine')]);
                $container->setDefinition($serviceId, $definition);
            } else {
                $definition->setArguments([$managerReference, $this->getClassMetadataDefinition($metadata)]);
            }

            $container->setDefinition($serviceId, $definition);

            $doctrineDefinition = new Definition($repositoryClass);
            $doctrineDefinition->addTag(ServiceRepositoryCompilerPass::REPOSITORY_SERVICE_TAG);
            $doctrineDefinition->setFactory([new Reference('service_container'), 'get']);
            $doctrineDefinition->setArguments([$serviceId]);

            $container->setDefinition($repositoryClass, $doctrineDefinition);
        }

        /** @var array $repositoryInterfaces */
        $repositoryInterfaces = class_implements($repositoryClass);

        /** @var array $repositoryParents */
        $repositoryParents = class_parents($repositoryClass);

        $typehintClasses = array_merge(
            $repositoryInterfaces,
            [$repositoryClass],
            $repositoryParents,
        );

        foreach ($typehintClasses as $typehintClass) {
            $container->registerAliasForArgument(
                $metadata->getServiceId('repository'),
                $typehintClass,
                $metadata->getHumanizedName() . ' repository',
            );
        }

        $container->setParameter(self::GENERIC_ENTITIES_PARAMETER, $genericEntities);
    }

    protected function addManager(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        parent::addManager($container, $metadata);

        $typehintClasses = [
            DeprecatedObjectManager::class,
            ObjectManager::class,
            EntityManagerInterface::class,
        ];

        foreach ($typehintClasses as $typehintClass) {
            $container->registerAliasForArgument(
                $metadata->getServiceId('manager'),
                $typehintClass,
                $metadata->getHumanizedName() . ' manager',
            );
        }
    }

    protected function getManagerServiceId(MetadataInterface $metadata): string
    {
        if ($objectManagerName = $this->getObjectManagerName($metadata)) {
            return sprintf('doctrine.orm.%s_entity_manager', $objectManagerName);
        }

        return 'doctrine.orm.entity_manager';
    }

    protected function getClassMetadataClassname(): string
    {
        return ClassMetadata::class;
    }
}
