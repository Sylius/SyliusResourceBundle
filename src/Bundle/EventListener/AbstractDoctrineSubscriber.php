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

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\ReflectionService;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

abstract class AbstractDoctrineSubscriber implements EventSubscriber
{
    protected RegistryInterface $resourceRegistry;

    private ?RuntimeReflectionService $reflectionService = null;

    public function __construct(RegistryInterface $resourceRegistry)
    {
        $this->resourceRegistry = $resourceRegistry;
    }

    protected function isResource(ClassMetadata $metadata): bool
    {
        return $metadata->getReflectionClass()->implementsInterface(ResourceInterface::class);
    }

    /**
     * @psalm-suppress InvalidReturnType
     */
    protected function getReflectionService(): ReflectionService
    {
        if ($this->reflectionService === null) {
            $this->reflectionService = new RuntimeReflectionService();
        }

        /** @psalm-suppress InvalidReturnStatement */
        return $this->reflectionService;
    }
}
