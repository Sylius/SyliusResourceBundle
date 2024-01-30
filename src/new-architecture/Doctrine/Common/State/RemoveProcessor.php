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

namespace Sylius\Resource\Doctrine\Common\State;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager as DoctrineObjectManager;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Reflection\ClassInfoTrait;
use Sylius\Resource\State\ProcessorInterface;

final class RemoveProcessor implements ProcessorInterface
{
    use ClassInfoTrait;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        if (!\is_object($data) || !$manager = $this->getManager($data)) {
            return null;
        }

        $manager->remove($data);
        $manager->flush();

        return null;
    }

    /**
     * Gets the Doctrine object manager associated with given data.
     */
    private function getManager(object $data): ?DoctrineObjectManager
    {
        return $this->managerRegistry->getManagerForClass($this->getObjectClass($data));
    }
}
