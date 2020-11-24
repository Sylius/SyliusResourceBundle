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
    public function getType(): string
    {
        return SyliusResourceBundle::DRIVER_DOCTRINE_ORM;
    }

    protected function addRepository(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $repositoryClassParameterName = sprintf('%s.repository.%s.class', $metadata->getApplicationName(), $metadata->getName());
        $repositoryClass = EntityRepository::class;

        if ($container->hasParameter($repositoryClassParameterName)) {
            $repositoryClass = $container->getParameter($repositoryClassParameterName);
        }

        if ($metadata->hasClass('repository')) {
            $repositoryClass = $metadata->getClass('repository');
        }

        $serviceId = $metadata->getServiceId('repository');
        $repositoryFactoryDef = $container->getDefinition('sylius.doctrine.orm.container_repository_factory');
        $managerReference = new Reference($metadata->getServiceId('manager'));
        $definition = new Definition($repositoryClass);
        $definition->setPublic(true);

        if ($repositoryClass === EntityRepository::class) {
            $entityClass = $metadata->getClass('model');

            $definition->setFactory([$managerReference, 'getRepository']);
            $definition->setArguments([$entityClass]);

            $container->setDefinition($serviceId, $definition);

            $repositoryFactoryDef->addMethodCall('addGenericEntity', [$entityClass]);
        } else {
            $definition->setArguments([$managerReference, $this->getClassMetadataDefinition($metadata)]);

            $container->setDefinition($serviceId, $definition);

            $doctrineDefinition = new Definition($repositoryClass);
            $doctrineDefinition->addTag(ServiceRepositoryCompilerPass::REPOSITORY_SERVICE_TAG);
            $doctrineDefinition->setFactory([new Reference('service_container'), 'get']);
            $doctrineDefinition->setArguments([$serviceId]);

            $container->setDefinition($repositoryClass, $doctrineDefinition);
        }

        $typehintClasses = array_merge(
            class_implements($repositoryClass),
            [$repositoryClass],
            class_parents($repositoryClass)
        );

        foreach ($typehintClasses as $typehintClass) {
            $container->registerAliasForArgument(
                $metadata->getServiceId('repository'),
                $typehintClass,
                $metadata->getHumanizedName() . ' repository'
            );
        }
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
                $metadata->getHumanizedName() . ' manager'
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
