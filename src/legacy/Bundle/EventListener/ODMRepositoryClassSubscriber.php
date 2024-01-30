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

use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

trigger_deprecation('sylius/resource-bundle', '1.3', 'The "%s" class is deprecated. Doctrine MongoDB and PHPCR support will no longer be supported in 2.0.', ODMRepositoryClassSubscriber::class);

final class ODMRepositoryClassSubscriber extends AbstractDoctrineSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $this->setCustomRepositoryClass($eventArgs->getClassMetadata());
    }

    private function setCustomRepositoryClass(ClassMetadata $metadata)
    {
        try {
            $resourceMetadata = $this->resourceRegistry->getByClass($metadata->getName());
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        if ($resourceMetadata->hasClass('repository')) {
            $metadata->setCustomRepositoryClass($resourceMetadata->getClass('repository'));
        }
    }
}
