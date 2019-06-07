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

namespace Sylius\Bundle\ResourceBundle\Doctrine;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Sylius\Component\Resource\Metadata\RegistryInterface;

final class ResourceMappingDriver implements MappingDriver
{
    /** @var MappingDriver */
    private $mappingDriver;

    /** @var RegistryInterface */
    private $resourceRegistry;

    public function __construct(MappingDriver $mappingDriver, RegistryInterface $resourceRegistry)
    {
        $this->mappingDriver = $mappingDriver;
        $this->resourceRegistry = $resourceRegistry;
    }

    public function loadMetadataForClass($className, ClassMetadata $metadata): void
    {
        $this->mappingDriver->loadMetadataForClass($className, $metadata);

        $this->convertResourceMappedSuperclass($metadata);
    }

    public function getAllClassNames(): iterable
    {
        return $this->mappingDriver->getAllClassNames();
    }

    public function isTransient($className): bool
    {
        return $this->mappingDriver->isTransient($className);
    }

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
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        if ($metadata->getName() !== $resourceMetadata->getClass('model')) {
            return;
        }

        $metadata->isMappedSuperclass = false;
    }
}
