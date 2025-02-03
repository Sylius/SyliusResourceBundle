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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use PHPCR\Util\NodeHelper;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

trigger_deprecation('sylius/resource-bundle', '1.3', 'The "%s" class is deprecated. Doctrine MongoDB and PHPCR support will no longer be supported in 2.0.', DefaultParentListener::class);

/**
 * Automatically set the parent brefore the creation.
 */
class DefaultParentListener
{
    /** @var DocumentManagerInterface */
    private $documentManager;

    public function __construct(
        DocumentManagerInterface $documentManager,
        private string $parentPath,
        private bool $autocreate = false,
        private bool $force = false,
    ) {
        $this->documentManager = $documentManager;
    }

    public function onPreCreate(ResourceControllerEvent $event)
    {
        $document = $event->getSubject();
        $class = $document::class;

        $this->resolveParent(
            $document,
            $this->documentManager->getClassMetadata($class),
        );
    }

    private function resolveParent(
        $document,
        ClassMetadata $metadata,
    ) {
        if (!$parentField = $metadata->parentMapping) {
            throw new \RuntimeException(sprintf(
                'A default parent path has been specified, but no parent mapping has been applied to document "%s"',
                $document::class,
            ));
        }

        if (false === $this->force) {
            $actualParent = $metadata->getFieldValue($document, $parentField);

            if ($actualParent) {
                return;
            }
        }

        $parentDocument = $this->documentManager->find(null, $this->parentPath);

        if (true === $this->autocreate && null === $parentDocument) {
            NodeHelper::createPath($this->documentManager->getPhpcrSession(), $this->parentPath);
            $parentDocument = $this->documentManager->find(null, $this->parentPath);
        }

        if (null === $parentDocument) {
            throw new \RuntimeException(sprintf(
                'Document at default parent path "%s" does not exist. `autocreate` was set to "%s"',
                $this->parentPath,
                $this->autocreate ? 'true' : 'false',
            ));
        }

        $metadata->setFieldValue($document, $parentField, $parentDocument);
    }
}
