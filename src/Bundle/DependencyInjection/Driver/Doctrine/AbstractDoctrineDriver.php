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

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\AbstractDriver;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

abstract class AbstractDoctrineDriver extends AbstractDriver
{
    protected function getClassMetadataDefinition(MetadataInterface $metadata): Definition
    {
        $definition = new Definition($this->getClassMetadataClassname());
        $definition
            ->setFactory([new Reference($this->getManagerServiceId($metadata)), 'getClassMetadata'])
            ->setArguments([$metadata->getClass('model')])
            ->setPublic(false)
        ;

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    protected function addManager(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $container->setAlias(
            $metadata->getServiceId('manager'),
            new Alias($this->getManagerServiceId($metadata), true)
        );
    }

    /**
     * Return the configured object managre name, or NULL if the default
     * manager should be used.
     */
    protected function getObjectManagerName(MetadataInterface $metadata): ?string
    {
        if (!$metadata->hasParameter('options')) {
            return null;
        }

        /** @var string[] $options */
        $options = $metadata->getParameter('options');

        return $options['object_manager'] ?? null;
    }

    abstract protected function getManagerServiceId(MetadataInterface $metadata): string;

    abstract protected function getClassMetadataClassname(): string;
}
