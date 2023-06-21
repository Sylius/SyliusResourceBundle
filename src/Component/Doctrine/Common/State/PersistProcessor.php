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

namespace Sylius\Component\Resource\Doctrine\Common\State;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager as DoctrineObjectManager;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Reflection\ClassInfoTrait;
use Sylius\Component\Resource\State\ProcessorInterface;

final class PersistProcessor implements ProcessorInterface
{
    use ClassInfoTrait;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        if (!is_object($data) || !$manager = $this->getManager($data)) {
            return $data;
        }

        if (!$manager->contains($data) || $this->isDeferredExplicit($manager, $data)) {
            $manager->persist($data);
        }

        $manager->flush();
        $manager->refresh($data);

        return $data;
    }

    /**
     * Gets the Doctrine object manager associated with given data.
     */
    private function getManager(object $data): ?DoctrineObjectManager
    {
        return $this->managerRegistry->getManagerForClass($this->getObjectClass($data));
    }

    /**
     * Checks if doctrine does not manage data automatically.
     */
    private function isDeferredExplicit(DoctrineObjectManager $manager, object $data): bool
    {
        $classMetadata = $manager->getClassMetadata($this->getObjectClass($data));
        if (($classMetadata instanceof ClassMetadataInfo || $classMetadata instanceof ClassMetadata) && method_exists($classMetadata, 'isChangeTrackingDeferredExplicit')) {
            return $classMetadata->isChangeTrackingDeferredExplicit();
        }

        return false;
    }
}
