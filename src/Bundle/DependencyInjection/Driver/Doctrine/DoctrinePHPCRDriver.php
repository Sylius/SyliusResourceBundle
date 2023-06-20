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

use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\DefaultParentListener;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\NameFilterListener;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\NameResolverListener;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

final class DoctrinePHPCRDriver extends AbstractDoctrineDriver
{
    public function load(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        trigger_deprecation(
            'sylius/resource-bundle',
            '1.3',
            'The "%s" class is deprecated. Doctrine MongoDB and PHPCR will no longer be supported in 2.0.',
            self::class,
        );

        parent::load($container, $metadata);

        $this->addResourceListeners($container, $metadata);
    }

    protected function addResourceListeners(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $defaultOptions = [
                // if no parent is given default to the parent path given here.
                'parent_path_default' => null,

                // auto-create the parent path if it does not exist.
                'parent_path_autocreate' => false,

                // set true to always override the parent path.
                'parent_path_force' => false,

                // automatically replace invalid characters in the node name
                // with a blank space.
                'name_filter' => true,

                // automatically resolve same-name-sibling conflicts.
                'name_resolver' => true,
        ];
        $metadataOptions = $metadata->hasParameter('options') ? $metadata->getParameter('options') : [];

        if ($diff = array_diff(array_keys($metadataOptions), array_keys($defaultOptions))) {
            throw new InvalidArgumentException(sprintf(
                'Unknown PHPCR-ODM configuration options: "%s"',
                implode('", "', $diff),
            ));
        }

        $options = array_merge(
            $defaultOptions,
            $metadataOptions,
        );

        $createEventName = sprintf('%s.%s.pre_%s', $metadata->getApplicationName(), $metadata->getName(), 'create');
        $updateEventName = sprintf('%s.%s.pre_%s', $metadata->getApplicationName(), $metadata->getName(), 'update');

        if ($options['parent_path_default']) {
            $defaultPath = new Definition(DefaultParentListener::class);
            $defaultPath->setArguments([
                new Reference($metadata->getServiceId('manager')),
                $options['parent_path_default'],
                $options['parent_path_autocreate'],
                $options['parent_path_force'],
            ]);
            $defaultPath->addTag('kernel.event_listener', [
                'event' => $createEventName,
                'method' => 'onPreCreate',
            ]);

            $container->setDefinition(
                sprintf(
                    '%s.resource.%s.doctrine.odm.phpcr.event_listener.default_path',
                    $metadata->getApplicationName(),
                    $metadata->getName(),
                ),
                $defaultPath,
            );
        }

        if ($options['name_filter']) {
            $nameFilter = new Definition(NameFilterListener::class);
            $nameFilter->setArguments([
                new Reference($metadata->getServiceId('manager')),
            ]);
            $nameFilter->addTag('kernel.event_listener', [
                'event' => $createEventName,
                'method' => 'onEvent',
            ]);
            $nameFilter->addTag('kernel.event_listener', [
                'event' => $updateEventName,
                'method' => 'onEvent',
            ]);

            $container->setDefinition(
                sprintf(
                    '%s.resource.%s.doctrine.odm.phpcr.event_listener.name_filter',
                    $metadata->getApplicationName(),
                    $metadata->getName(),
                ),
                $nameFilter,
            );
        }

        if ($options['name_resolver']) {
            $nameResolver = new Definition(NameResolverListener::class);
            $nameResolver->setArguments([
                new Reference($metadata->getServiceId('manager')),
            ]);
            $nameResolver->addTag('kernel.event_listener', [
                'event' => $createEventName,
                'method' => 'onEvent',
            ]);
            $nameResolver->addTag('kernel.event_listener', [
                'event' => $updateEventName,
                'method' => 'onEvent',
            ]);

            $container->setDefinition(
                sprintf(
                    '%s.resource.%s.doctrine.odm.phpcr.event_listener.name_resolver',
                    $metadata->getApplicationName(),
                    $metadata->getName(),
                ),
                $nameResolver,
            );
        }
    }

    public function getType(): string
    {
        return SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM;
    }

    protected function addRepository(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $repositoryClass = new Parameter('sylius.phpcr_odm.repository.class');

        if ($metadata->hasClass('repository')) {
            $repositoryClass = $metadata->getClass('repository');
        }

        $definition = new Definition($repositoryClass);
        $definition->setArguments([
            new Reference($metadata->getServiceId('manager')),
            $this->getClassMetadataDefinition($metadata),
        ]);
        $definition->addTag('sylius.repository');

        $container->setDefinition($metadata->getServiceId('repository'), $definition);
    }

    protected function getManagerServiceId(MetadataInterface $metadata): string
    {
        if ($objectManagerName = $this->getObjectManagerName($metadata)) {
            return sprintf('doctrine_phpcr.odm.%s_document_manager', $objectManagerName);
        }

        return 'doctrine_phpcr.odm.document_manager';
    }

    protected function getClassMetadataClassname(): string
    {
        return 'Doctrine\\ODM\\PHPCR\\Mapping\\ClassMetadata';
    }
}
