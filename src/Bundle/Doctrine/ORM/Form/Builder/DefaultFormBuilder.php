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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\FormBuilderInterface;

class DefaultFormBuilder implements DefaultFormBuilderInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function build(MetadataInterface $metadata, FormBuilderInterface $formBuilder, array $options): void
    {
        $classMetadata = $this->entityManager->getClassMetadata($metadata->getClass('model'));

        if (1 < count($classMetadata->identifier)) {
            throw new \RuntimeException('The default form factory does not support entity classes with multiple primary keys.');
        }

        $this->doBuild($classMetadata, $formBuilder);
    }

    private function doBuild(ClassMetadataInfo $classMetadata, FormBuilderInterface $formBuilder): void
    {
        $fields = (array) $classMetadata->fieldNames;

        if (!$classMetadata->isIdentifierNatural()) {
            $fields = array_diff($fields, $classMetadata->identifier);
        }

        foreach ($fields as $fieldName) {
            $options = [];

            // Skip fields coming from embeddables
            if (strpos($fieldName, '.') !== false) {
                continue;
            }

            if (in_array($fieldName, ['createdAt', 'updatedAt'], true)) {
                continue;
            }

            if (Types::DATETIME_MUTABLE === $classMetadata->getTypeOfField($fieldName)) {
                $options = ['widget' => 'single_text'];
            }

            $formBuilder->add($fieldName, null, $options);
        }

        foreach ($classMetadata->embeddedClasses as $fieldName => $embeddedMapping) {
            $nestedFormBuilder = $formBuilder->create($fieldName, null, ['data_class' => $embeddedMapping['class'], 'compound' => true]);

            $this->doBuild($this->entityManager->getClassMetadata($embeddedMapping['class']), $nestedFormBuilder);

            $formBuilder->add($nestedFormBuilder);
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $associationMapping) {
            if (ClassMetadataInfo::ONE_TO_MANY !== $associationMapping['type']) {
                $formBuilder->add($fieldName, null, ['choice_label' => 'id']);
            }
        }
    }
}
