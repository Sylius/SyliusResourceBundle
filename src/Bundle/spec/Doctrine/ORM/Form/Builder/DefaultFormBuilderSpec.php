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

namespace spec\Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class DefaultFormBuilderSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager): void
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_a_default_form_builder(): void
    {
        $this->shouldImplement(DefaultFormBuilderInterface::class);
    }

    function it_does_not_support_entities_with_multiple_primary_keys(
        MetadataInterface $metadata,
        FormBuilderInterface $formBuilder,
        EntityManagerInterface $entityManager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $metadata->getClass('model')->willReturn('AppBundle\Entity\Book');
        $entityManager->getClassMetadata('AppBundle\Entity\Book')->willReturn($classMetadataInfo);
        $classMetadataInfo->identifier = ['id', 'slug'];

        $this
            ->shouldThrow(\RuntimeException::class)
            ->during('build', [$metadata, $formBuilder, []])
        ;
    }

    function it_excludes_non_natural_identifier_from_the_field_list(
        MetadataInterface $metadata,
        FormBuilderInterface $formBuilder,
        EntityManagerInterface $entityManager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $metadata->getClass('model')->willReturn('AppBundle\Entity\Book');
        $entityManager->getClassMetadata('AppBundle\Entity\Book')->willReturn($classMetadataInfo);
        $classMetadataInfo->fieldNames = ['id', 'name', 'description', 'enabled'];
        $classMetadataInfo->identifier = ['id'];
        $classMetadataInfo->isIdentifierNatural()->willReturn(false);
        $classMetadataInfo->getAssociationMappings()->willReturn([]);

        $classMetadataInfo->getTypeOfField('name')->willReturn(Types::STRING);
        $classMetadataInfo->getTypeOfField('description')->willReturn(Types::TEXT);
        $classMetadataInfo->getTypeOfField('enabled')->willReturn(Types::BOOLEAN);

        $formBuilder->add('name', null, [])->willReturn($formBuilder);
        $formBuilder->add('description', null, [])->willReturn($formBuilder);
        $formBuilder->add('enabled', null, [])->willReturn($formBuilder);

        $formBuilder->add('id', Argument::cetera())->shouldNotBeCalled();
        $formBuilder->add('name', null, [])->shouldBeCalled();
        $formBuilder->add('description', null, [])->shouldBeCalled();
        $formBuilder->add('enabled', null, [])->shouldBeCalled();

        $this->build($metadata, $formBuilder, []);
    }

    function it_does_not_exclude_natural_identifier_from_the_field_list(
        MetadataInterface $metadata,
        FormBuilderInterface $formBuilder,
        EntityManagerInterface $entityManager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $metadata->getClass('model')->willReturn('AppBundle\Entity\Book');
        $entityManager->getClassMetadata('AppBundle\Entity\Book')->willReturn($classMetadataInfo);
        $classMetadataInfo->fieldNames = ['id', 'name', 'description', 'enabled'];
        $classMetadataInfo->identifier = ['id'];
        $classMetadataInfo->isIdentifierNatural()->willReturn(true);
        $classMetadataInfo->getAssociationMappings()->willReturn([]);

        $classMetadataInfo->getTypeOfField('id')->willReturn(Types::INTEGER);
        $classMetadataInfo->getTypeOfField('name')->willReturn(Types::STRING);
        $classMetadataInfo->getTypeOfField('description')->willReturn(Types::TEXT);
        $classMetadataInfo->getTypeOfField('enabled')->willReturn(Types::BOOLEAN);

        $formBuilder->add('id', null, [])->willReturn($formBuilder);
        $formBuilder->add('name', null, [])->willReturn($formBuilder);
        $formBuilder->add('description', null, [])->willReturn($formBuilder);
        $formBuilder->add('enabled', null, [])->willReturn($formBuilder);

        $formBuilder->add('id', null, [])->shouldBeCalled();
        $formBuilder->add('name', null, [])->shouldBeCalled();
        $formBuilder->add('description', null, [])->shouldBeCalled();
        $formBuilder->add('enabled', null, [])->shouldBeCalled();

        $this->build($metadata, $formBuilder, []);
    }

    function it_uses_metadata_to_create_appropriate_fields(
        MetadataInterface $metadata,
        FormBuilderInterface $formBuilder,
        EntityManagerInterface $entityManager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $metadata->getClass('model')->willReturn('AppBundle\Entity\Book');
        $entityManager->getClassMetadata('AppBundle\Entity\Book')->willReturn($classMetadataInfo);
        $classMetadataInfo->fieldNames = ['name', 'description', 'enabled'];
        $classMetadataInfo->isIdentifierNatural()->willReturn(true);
        $classMetadataInfo->getAssociationMappings()->willReturn([]);

        $classMetadataInfo->getTypeOfField('name')->willReturn(Types::STRING);
        $classMetadataInfo->getTypeOfField('description')->willReturn(Types::TEXT);
        $classMetadataInfo->getTypeOfField('enabled')->willReturn(Types::BOOLEAN);

        $formBuilder->add('name', null, [])->willReturn($formBuilder);
        $formBuilder->add('description', null, [])->willReturn($formBuilder);
        $formBuilder->add('enabled', null, [])->willReturn($formBuilder);

        $formBuilder->add('name', null, [])->shouldBeCalled();
        $formBuilder->add('description', null, [])->shouldBeCalled();
        $formBuilder->add('enabled', null, [])->shouldBeCalled();

        $this->build($metadata, $formBuilder, []);
    }

    function it_uses_single_text_widget_for_datetime_field(
        MetadataInterface $metadata,
        FormBuilderInterface $formBuilder,
        EntityManagerInterface $entityManager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $metadata->getClass('model')->willReturn('AppBundle\Entity\Book');
        $entityManager->getClassMetadata('AppBundle\Entity\Book')->willReturn($classMetadataInfo);
        $classMetadataInfo->fieldNames = ['name', 'description', 'enabled', 'publishedAt'];
        $classMetadataInfo->isIdentifierNatural()->willReturn(true);
        $classMetadataInfo->getAssociationMappings()->willReturn([]);

        $classMetadataInfo->getTypeOfField('name')->willReturn(Types::STRING);
        $classMetadataInfo->getTypeOfField('description')->willReturn(Types::TEXT);
        $classMetadataInfo->getTypeOfField('enabled')->willReturn(Types::BOOLEAN);
        $classMetadataInfo->getTypeOfField('publishedAt')->willReturn(Types::DATETIME_MUTABLE);

        $formBuilder->add('name', null, [])->willReturn($formBuilder);
        $formBuilder->add('description', null, [])->willReturn($formBuilder);
        $formBuilder->add('enabled', null, [])->willReturn($formBuilder);
        $formBuilder->add('publishedAt', null, ['widget' => 'single_text'])->willReturn($formBuilder);

        $formBuilder->add('name', null, [])->shouldBeCalled();
        $formBuilder->add('description', null, [])->shouldBeCalled();
        $formBuilder->add('enabled', null, [])->shouldBeCalled();
        $formBuilder->add('publishedAt', null, ['widget' => 'single_text'])->shouldBeCalled();

        $this->build($metadata, $formBuilder, []);
    }

    function it_also_creates_fields_for_relations_other_than_one_to_many(
        MetadataInterface $metadata,
        FormBuilderInterface $formBuilder,
        EntityManagerInterface $entityManager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $metadata->getClass('model')->willReturn('AppBundle\Entity\Book');
        $entityManager->getClassMetadata('AppBundle\Entity\Book')->willReturn($classMetadataInfo);
        $classMetadataInfo->fieldNames = ['name', 'description', 'enabled', 'publishedAt'];
        $classMetadataInfo->isIdentifierNatural()->willReturn(true);
        $classMetadataInfo->getAssociationMappings()->willReturn([
            'category' => ['type' => ClassMetadataInfo::MANY_TO_ONE],
            'users' => ['type' => ClassMetadataInfo::ONE_TO_MANY],
        ]);

        $classMetadataInfo->getTypeOfField('name')->willReturn(Types::STRING);
        $classMetadataInfo->getTypeOfField('description')->willReturn(Types::TEXT);
        $classMetadataInfo->getTypeOfField('enabled')->willReturn(Types::BOOLEAN);
        $classMetadataInfo->getTypeOfField('publishedAt')->willReturn(Types::DATETIME_MUTABLE);

        $formBuilder->add('name', null, [])->willReturn($formBuilder);
        $formBuilder->add('description', null, [])->willReturn($formBuilder);
        $formBuilder->add('enabled', null, [])->willReturn($formBuilder);
        $formBuilder->add('publishedAt', null, ['widget' => 'single_text'])->willReturn($formBuilder);
        $formBuilder->add('category', null, ['choice_label' => 'id'])->willReturn($formBuilder);
        $formBuilder->add('users', Argument::cetera())->willReturn($formBuilder);

        $formBuilder->add('name', null, [])->shouldBeCalled();
        $formBuilder->add('description', null, [])->shouldBeCalled();
        $formBuilder->add('enabled', null, [])->shouldBeCalled();
        $formBuilder->add('publishedAt', null, ['widget' => 'single_text'])->shouldBeCalled();
        $formBuilder->add('category', null, ['choice_label' => 'id'])->shouldBeCalled();
        $formBuilder->add('users', Argument::cetera())->shouldNotBeCalled();

        $this->build($metadata, $formBuilder, []);
    }

    function it_excludes_common_fields_like_createdAt_and_updatedAt(
        MetadataInterface $metadata,
        FormBuilderInterface $formBuilder,
        EntityManagerInterface $entityManager,
        ClassMetadataInfo $classMetadataInfo,
    ): void {
        $metadata->getClass('model')->willReturn('AppBundle\Entity\Book');
        $entityManager->getClassMetadata('AppBundle\Entity\Book')->willReturn($classMetadataInfo);
        $classMetadataInfo->fieldNames = ['name', 'description', 'enabled', 'createdAt', 'updatedAt'];
        $classMetadataInfo->isIdentifierNatural()->willReturn(true);
        $classMetadataInfo->getAssociationMappings()->willReturn([]);

        $classMetadataInfo->getTypeOfField('name')->willReturn(Types::STRING);
        $classMetadataInfo->getTypeOfField('description')->willReturn(Types::TEXT);
        $classMetadataInfo->getTypeOfField('enabled')->willReturn(Types::BOOLEAN);
        $classMetadataInfo->getTypeOfField('createdAt')->willReturn(Types::DATETIME_MUTABLE);
        $classMetadataInfo->getTypeOfField('updatedAt')->willReturn(Types::DATETIME_MUTABLE);

        $formBuilder->add('name', null, [])->willReturn($formBuilder);
        $formBuilder->add('description', null, [])->willReturn($formBuilder);
        $formBuilder->add('enabled', null, [])->willReturn($formBuilder);
        $formBuilder->add('createdAt', Argument::cetera())->willReturn($formBuilder);
        $formBuilder->add('updatedAt', Argument::cetera())->willReturn($formBuilder);

        $formBuilder->add('name', null, [])->shouldBeCalled();
        $formBuilder->add('description', null, [])->shouldBeCalled();
        $formBuilder->add('enabled', null, [])->shouldBeCalled();
        $formBuilder->add('createdAt', Argument::cetera())->shouldNotBeCalled();
        $formBuilder->add('updatedAt', Argument::cetera())->shouldNotBeCalled();

        $this->build($metadata, $formBuilder, []);
    }
}
