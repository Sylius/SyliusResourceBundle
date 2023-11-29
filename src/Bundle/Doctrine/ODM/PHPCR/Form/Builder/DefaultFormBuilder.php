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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\Form\Builder;

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\FormBuilderInterface;

trigger_deprecation('sylius/resource-bundle', '1.3', 'The "%s" class is deprecated. Doctrine MongoDB and PHPCR support will no longer be supported in 2.0.', DefaultFormBuilder::class);

class DefaultFormBuilder implements DefaultFormBuilderInterface
{
    /** @var DocumentManagerInterface */
    private $documentManager;

    public function __construct(
        DocumentManagerInterface $documentManager,
    ) {
        $this->documentManager = $documentManager;
    }

    public function build(MetadataInterface $metadata, FormBuilderInterface $formBuilder, array $options): void
    {
        $classMetadata = $this->documentManager->getClassMetadata($metadata->getClass('model'));

        // the field mappings should only contain standard value mappings
        foreach ($classMetadata->fieldMappings as $fieldName) {
            if ($fieldName === $classMetadata->uuidFieldName) {
                continue;
            }
            if ($fieldName === $classMetadata->nodename) {
                continue;
            }

            $options = [];

            $mapping = $classMetadata->mappings[$fieldName];

            if ($mapping['nullable'] === false) {
                $options['required'] = true;
            }

            $formBuilder->add($fieldName, null, $options);
        }
    }
}
