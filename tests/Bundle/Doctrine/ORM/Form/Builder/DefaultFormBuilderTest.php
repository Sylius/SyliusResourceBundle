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

namespace Sylius\Bundle\ResourceBundle\Tests\Doctrine\ORM\Form\Builder;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder\DefaultFormBuilder;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class DefaultFormBuilderTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private MockObject $entityManagerMock;
    private DefaultFormBuilder $defaultFormBuilder;
    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->defaultFormBuilder = new DefaultFormBuilder($this->entityManagerMock);
    }

    function testADefaultFormBuilder(): void
    {
        $this->assertInstanceOf(DefaultFormBuilderInterface::class, $this->defaultFormBuilder);
    }

    function testDoesNotSupportEntitiesWithMultiplePrimaryKeys(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var FormBuilderInterface|MockObject $formBuilderMock */
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        /** @var ClassMetadata|MockObject $classMetadataMock */
        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');
        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($classMetadataMock);
        $classMetadataMock->identifier = ['id', 'slug'];
        $this->expectException(\RuntimeException::class);
        $this->defaultFormBuilder->build($metadataMock, $formBuilderMock, []);
    }

    function testExcludesNonNaturalIdentifierFromTheFieldList(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var FormBuilderInterface|MockObject $formBuilderMock */
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        /** @var ClassMetadata|MockObject $classMetadataMock */
        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');
        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($classMetadataMock);
        $classMetadataMock->fieldNames = ['id', 'name', 'description', 'enabled'];
        $classMetadataMock->identifier = ['id'];
        $classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(false);
        $classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $classMetadataMock->expects($this->exactly(3))->method('getTypeOfField')->willReturnMap([['name', Types::STRING], ['description', Types::TEXT], ['enabled', Types::BOOLEAN]]);
        $formBuilderMock->expects($this->exactly(3))->method('add')->willReturnMap([['name', null, [], $formBuilderMock], ['description', null, [], $formBuilderMock], ['enabled', null, [], $formBuilderMock], ['id', Argument::cetera()], ['name', null, []], ['description', null, []], ['enabled', null, []]]);

        $this->defaultFormBuilder->build($metadataMock, $formBuilderMock, []);
    }

    function testDoesNotExcludeNaturalIdentifierFromTheFieldList(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var FormBuilderInterface|MockObject $formBuilderMock */
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        /** @var ClassMetadata|MockObject $classMetadataMock */
        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');
        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($classMetadataMock);
        $classMetadataMock->fieldNames = ['id', 'name', 'description', 'enabled'];
        $classMetadataMock->identifier = ['id'];
        $classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $classMetadataMock->expects($this->exactly(4))->method('getTypeOfField')->willReturnMap([['id', Types::INTEGER], ['name', Types::STRING], ['description', Types::TEXT], ['enabled', Types::BOOLEAN]]);
        $formBuilderMock->expects($this->exactly(4))->method('add')->willReturnMap([['id', null, [], $formBuilderMock], ['name', null, [], $formBuilderMock], ['description', null, [], $formBuilderMock], ['enabled', null, [], $formBuilderMock], ['id', null, []], ['name', null, []], ['description', null, []], ['enabled', null, []]]);

        $this->defaultFormBuilder->build($metadataMock, $formBuilderMock, []);
    }

    function testUsesMetadataToCreateAppropriateFields(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var FormBuilderInterface|MockObject $formBuilderMock */
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        /** @var ClassMetadata|MockObject $classMetadataMock */
        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');
        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($classMetadataMock);
        $classMetadataMock->fieldNames = ['name', 'description', 'enabled'];
        $classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $classMetadataMock->expects($this->exactly(3))->method('getTypeOfField')->willReturnMap([['name', Types::STRING], ['description', Types::TEXT], ['enabled', Types::BOOLEAN]]);
        $formBuilderMock->expects($this->exactly(3))->method('add')->willReturnMap([['name', null, [], $formBuilderMock], ['description', null, [], $formBuilderMock], ['enabled', null, [], $formBuilderMock], ['name', null, []], ['description', null, []], ['enabled', null, []]]);

        $this->defaultFormBuilder->build($metadataMock, $formBuilderMock, []);
    }

    function testUsesSingleTextWidgetForDatetimeField(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var FormBuilderInterface|MockObject $formBuilderMock */
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        /** @var ClassMetadata|MockObject $classMetadataMock */
        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');
        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($classMetadataMock);
        $classMetadataMock->fieldNames = ['name', 'description', 'enabled', 'publishedAt'];
        $classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $classMetadataMock->expects($this->exactly(4))->method('getTypeOfField')->willReturnMap([['name', Types::STRING], ['description', Types::TEXT], ['enabled', Types::BOOLEAN], ['publishedAt', Types::DATETIME_MUTABLE]]);
        $formBuilderMock->expects($this->exactly(4))->method('add')->willReturnMap([['name', null, [], $formBuilderMock], ['description', null, [], $formBuilderMock], ['enabled', null, [], $formBuilderMock], ['publishedAt', null, ['widget' => 'single_text'], $formBuilderMock], ['name', null, []], ['description', null, []], ['enabled', null, []], ['publishedAt', null, ['widget' => 'single_text']]]);

        $this->defaultFormBuilder->build($metadataMock, $formBuilderMock, []);
    }

    function testAlsoCreatesFieldsForRelationsOtherThanOneToMany(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var FormBuilderInterface|MockObject $formBuilderMock */
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        /** @var ClassMetadata|MockObject $classMetadataMock */
        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');
        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($classMetadataMock);
        $classMetadataMock->fieldNames = ['name', 'description', 'enabled', 'publishedAt'];
        $classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([
            'category' => ['type' => ClassMetadata::MANY_TO_ONE],
            'users' => ['type' => ClassMetadata::ONE_TO_MANY],
        ]);
        $classMetadataMock->expects($this->exactly(4))->method('getTypeOfField')->willReturnMap([['name', Types::STRING], ['description', Types::TEXT], ['enabled', Types::BOOLEAN], ['publishedAt', Types::DATETIME_MUTABLE]]);
        $formBuilderMock->expects($this->exactly(5))->method('add')->willReturnMap([['name', null, [], $formBuilderMock], ['description', null, [], $formBuilderMock], ['enabled', null, [], $formBuilderMock], ['publishedAt', null, ['widget' => 'single_text'], $formBuilderMock], ['category', null, ['choice_label' => 'id'], $formBuilderMock], ['users', Argument::cetera(), $formBuilderMock], ['name', null, []], ['description', null, []], ['enabled', null, []], ['publishedAt', null, ['widget' => 'single_text']], ['category', null, ['choice_label' => 'id']], ['users', Argument::cetera()]]);

        $this->defaultFormBuilder->build($metadataMock, $formBuilderMock, []);
    }

    function testExcludesCommonFieldsLikeCreatedAtAndUpdatedAt(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var FormBuilderInterface|MockObject $formBuilderMock */
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        /** @var ClassMetadata|MockObject $classMetadataMock */
        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');
        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($classMetadataMock);
        $classMetadataMock->fieldNames = ['name', 'description', 'enabled', 'createdAt', 'updatedAt'];
        $classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $classMetadataMock->expects($this->exactly(3))->method('getTypeOfField')->willReturnMap([['name', Types::STRING], ['description', Types::TEXT], ['enabled', Types::BOOLEAN], ['createdAt', Types::DATETIME_MUTABLE], ['updatedAt', Types::DATETIME_MUTABLE]]);
        $formBuilderMock->expects($this->exactly(3))->method('add')->willReturnMap([['name', null, [], $formBuilderMock], ['description', null, [], $formBuilderMock], ['enabled', null, [], $formBuilderMock], ['createdAt', Argument::cetera(), $formBuilderMock], ['updatedAt', Argument::cetera(), $formBuilderMock], ['name', null, []], ['description', null, []], ['enabled', null, []], ['createdAt', Argument::cetera()], ['updatedAt', Argument::cetera()]]);

        $this->defaultFormBuilder->build($metadataMock, $formBuilderMock, []);
    }
}
