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

namespace Sylius\Component\Resource\Doctrine\Common\State;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager as DoctrineObjectManager;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Reflection\ClassInfoTrait;
use Sylius\Component\Resource\State\ProcessorInterface;

final class RemoveProcessor implements ProcessorInterface
{
    use ClassInfoTrait;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $data = \is_array($data) ? $data : [$data];

        foreach ($data as $row) {
            if (!\is_object($row) || !$manager = $this->getManager($row)) {
                return null;
            }

            $manager->remove($row);
            $manager->flush();
        }

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
