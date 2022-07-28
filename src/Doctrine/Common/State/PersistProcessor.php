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

namespace Sylius\Resource\Doctrine\Common\State;

use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Resource\State\ProcessorInterface;

final class PersistProcessor implements ProcessorInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function process(mixed $data, RequestConfiguration $configuration)
    {
        $manager = $this->managerRegistry->getManagerForClass($configuration->getMetadata()->getClass('model'));

        if (null === $manager) {
            return;
        }

        if (!$manager->contains($data)) {
            $manager->persist($data);
        }

        $manager->flush();
        $manager->refresh($data);

        return $data;
    }
}
