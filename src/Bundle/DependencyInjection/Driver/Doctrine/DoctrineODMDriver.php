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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine;

use Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\TranslatableRepository;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

final class DoctrineODMDriver extends AbstractDoctrineDriver
{
    public function getType(): string
    {
        return SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM;
    }

    public function load(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        trigger_deprecation(
            'sylius/resource-bundle',
            '1.3',
            'The "%s" class is deprecated. Doctrine MongoDB and PHPCR will no longer be supported in 2.0.',
            self::class,
        );

        parent::load($container, $metadata);
    }

    protected function addRepository(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $modelClass = $metadata->getClass('model');

        /** @var array $modelInterfaces */
        $modelInterfaces = class_implements($modelClass);

        $repositoryClass = in_array(TranslatableInterface::class, $modelInterfaces)
            ? TranslatableRepository::class
            : new Parameter('sylius.mongodb.odm.repository.class')
        ;

        if ($metadata->hasClass('repository')) {
            $repositoryClass = $metadata->getClass('repository');
        }

        $unitOfWorkDefinition = new Definition('Doctrine\\ODM\\MongoDB\\UnitOfWork');
        $unitOfWorkDefinition
            ->setFactory([new Reference($this->getManagerServiceId($metadata)), 'getUnitOfWork'])
            ->setPublic(false)
        ;

        $definition = new Definition($repositoryClass);
        $definition->setArguments([
            new Reference($metadata->getServiceId('manager')),
            $unitOfWorkDefinition,
            $this->getClassMetadataDefinition($metadata),
        ]);
        $definition->addTag('sylius.repository');

        $container->setDefinition($metadata->getServiceId('repository'), $definition);
    }

    protected function getManagerServiceId(MetadataInterface $metadata): string
    {
        if ($objectManagerName = $this->getObjectManagerName($metadata)) {
            return sprintf('doctrine_mongodb.odm.%s_document_manager', $objectManagerName);
        }

        return 'doctrine_mongodb.odm.document_manager';
    }

    protected function getClassMetadataClassname(): string
    {
        return 'Doctrine\\ODM\\MongoDB\\Mapping\\ClassMetadata';
    }
}
