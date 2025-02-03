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

namespace Sylius\Bundle\ResourceBundle\Doctrine;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Sylius\Resource\Metadata\RegistryInterface;

/**
 * It needs to extend MappingDriverChain in order to be compatible with Gedmo/DoctrineExtensions.
 *
 * @see \Gedmo\Mapping\ExtensionMetadataFactory::getDriver()
 */
final class ResourceMappingDriverChain extends MappingDriverChain
{
    public function __construct(MappingDriver $mappingDriver, private RegistryInterface $resourceRegistry)
    {
        $this->setDefaultDriver($mappingDriver);
    }

    public function loadMetadataForClass($className, ClassMetadata $metadata): void
    {
        parent::loadMetadataForClass($className, $metadata);

        $this->convertResourceMappedSuperclass($metadata);
    }

    /**
     * @psalm-suppress NoInterfaceProperties https://github.com/vimeo/psalm/issues/2206
     */
    private function convertResourceMappedSuperclass(ClassMetadata $metadata): void
    {
        if (!isset($metadata->isMappedSuperclass)) {
            return;
        }

        if (false === $metadata->isMappedSuperclass) {
            return;
        }

        try {
            $resourceMetadata = $this->resourceRegistry->getByClass($metadata->getName());
        } catch (\InvalidArgumentException) {
            return;
        }

        if ($metadata->getName() !== $resourceMetadata->getClass('model')) {
            return;
        }

        $metadata->isMappedSuperclass = false;
    }
}
