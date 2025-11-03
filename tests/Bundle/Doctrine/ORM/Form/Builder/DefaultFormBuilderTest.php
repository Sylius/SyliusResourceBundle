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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\Form\Builder\DefaultFormBuilder;
use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class DefaultFormBuilderTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManagerMock;

    private DefaultFormBuilder $defaultFormBuilder;

    private MetadataInterface|MockObject $metadataMock;

    private FormBuilderInterface|MockObject $formBuilderMock;

    private ClassMetadata|MockObject $classMetadataMock;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->defaultFormBuilder = new DefaultFormBuilder($this->entityManagerMock);
        $this->metadataMock = $this->createMock(MetadataInterface::class);
        $this->formBuilderMock = $this->createMock(FormBuilderInterface::class);
        $this->classMetadataMock = $this->createMock(ClassMetadata::class);
    }

    public function testADefaultFormBuilder(): void
    {
        $this->assertInstanceOf(DefaultFormBuilderInterface::class, $this->defaultFormBuilder);
    }

    public function testDoesNotSupportEntitiesWithMultiplePrimaryKeys(): void
    {
        $this->metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');

        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($this->classMetadataMock);

        $this->classMetadataMock->identifier = ['id', 'slug'];

        $this->expectException(\RuntimeException::class);

        $this->defaultFormBuilder->build($this->metadataMock, $this->formBuilderMock, []);
    }

    public function testExcludesNonNaturalIdentifierFromTheFieldList(): void
    {
        $this->metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');

        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($this->classMetadataMock);

        $this->classMetadataMock->fieldNames = ['id', 'name', 'description', 'enabled'];
        $this->classMetadataMock->identifier = ['id'];
        $this->classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(false);
        $this->classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $this->classMetadataMock
            ->expects($this->exactly(3))
            ->method('getTypeOfField')
            ->willReturnMap([
                ['name', Types::STRING],
                ['description', Types::TEXT],
                ['enabled', Types::BOOLEAN],
            ])
        ;

        $this->formBuilderMock
            ->expects($this->exactly(3))
            ->method('add')
            ->willReturnMap([
                ['name', null, [], $this->formBuilderMock],
                ['description', null, [], $this->formBuilderMock],
                ['enabled', null, [], $this->formBuilderMock],
                ['id', Argument::cetera()],
                ['name', null, []],
                ['description', null, []],
                ['enabled', null, []],
            ])
        ;

        $this->defaultFormBuilder->build($this->metadataMock, $this->formBuilderMock, []);
    }

    public function testDoesNotExcludeNaturalIdentifierFromTheFieldList(): void
    {
        $this->metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');

        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($this->classMetadataMock);

        $this->classMetadataMock->fieldNames = ['id', 'name', 'description', 'enabled'];
        $this->classMetadataMock->identifier = ['id'];
        $this->classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $this->classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $this->classMetadataMock
            ->expects($this->exactly(4))
            ->method('getTypeOfField')
            ->willReturnMap([
                ['id', Types::INTEGER],
                ['name', Types::STRING],
                ['description', Types::TEXT],
                ['enabled', Types::BOOLEAN],
            ])
        ;

        $this->formBuilderMock
            ->expects($this->exactly(4))
            ->method('add')
            ->willReturnMap([
                ['id', null, [], $this->formBuilderMock],
                ['name', null, [], $this->formBuilderMock],
                ['description', null, [], $this->formBuilderMock],
                ['enabled', null, [], $this->formBuilderMock],
                ['id', null, []],
                ['name', null, []],
                ['description', null, []],
                ['enabled', null, []],
            ])
        ;

        $this->defaultFormBuilder->build($this->metadataMock, $this->formBuilderMock, []);
    }

    public function testUsesMetadataToCreateAppropriateFields(): void
    {
        $this->metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');

        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($this->classMetadataMock);

        $this->classMetadataMock->fieldNames = ['name', 'description', 'enabled'];
        $this->classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $this->classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $this->classMetadataMock
            ->expects($this->exactly(3))
            ->method('getTypeOfField')
            ->willReturnMap([
                ['name', Types::STRING],
                ['description', Types::TEXT],
                ['enabled', Types::BOOLEAN],
            ])
        ;

        $this->formBuilderMock
            ->expects($this->exactly(3))
            ->method('add')
            ->willReturnMap([
                ['name', null, [], $this->formBuilderMock],
                ['description', null, [], $this->formBuilderMock],
                ['enabled', null, [], $this->formBuilderMock],
                ['name', null, []],
                ['description', null, []],
                ['enabled', null, []],
            ])
        ;

        $this->defaultFormBuilder->build($this->metadataMock, $this->formBuilderMock, []);
    }

    public function testUsesSingleTextWidgetForDatetimeField(): void
    {
        $this->metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');

        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($this->classMetadataMock);

        $this->classMetadataMock->fieldNames = ['name', 'description', 'enabled', 'publishedAt'];
        $this->classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $this->classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $this->classMetadataMock
            ->expects($this->exactly(4))
            ->method('getTypeOfField')
            ->willReturnMap([
                ['name', Types::STRING],
                ['description', Types::TEXT],
                ['enabled', Types::BOOLEAN],
                ['publishedAt', Types::DATETIME_MUTABLE],
            ])
        ;

        $this->formBuilderMock
            ->expects($this->exactly(4))
            ->method('add')
            ->willReturnMap([
                ['name', null, [], $this->formBuilderMock],
                ['description', null, [], $this->formBuilderMock],
                ['enabled', null, [], $this->formBuilderMock],
                ['publishedAt', null, ['widget' => 'single_text'], $this->formBuilderMock],
                ['name', null, []],
                ['description', null, []],
                ['enabled', null, []],
                ['publishedAt', null, ['widget' => 'single_text']],
            ])
        ;

        $this->defaultFormBuilder->build($this->metadataMock, $this->formBuilderMock, []);
    }

    public function testAlsoCreatesFieldsForRelationsOtherThanOneToMany(): void
    {
        $this->metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');

        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($this->classMetadataMock);

        $this->classMetadataMock->fieldNames = ['name', 'description', 'enabled', 'publishedAt'];
        $this->classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $this->classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([
            'category' => ['type' => ClassMetadata::MANY_TO_ONE],
            'users' => ['type' => ClassMetadata::ONE_TO_MANY],
        ]);
        $this->classMetadataMock
            ->expects($this->exactly(4))
            ->method('getTypeOfField')
            ->willReturnMap([
                ['name', Types::STRING],
                ['description', Types::TEXT],
                ['enabled', Types::BOOLEAN],
                ['publishedAt', Types::DATETIME_MUTABLE],
            ])
        ;

        $this->formBuilderMock
            ->expects($this->exactly(5))
            ->method('add')
            ->willReturnMap([
                ['name', null, [], $this->formBuilderMock],
                ['description', null, [], $this->formBuilderMock],
                ['enabled', null, [], $this->formBuilderMock],
                ['publishedAt', null, ['widget' => 'single_text'], $this->formBuilderMock],
                ['category', null, ['choice_label' => 'id'], $this->formBuilderMock],
                ['users', Argument::cetera(), $this->formBuilderMock],
                ['name', null, []],
                ['description', null, []],
                ['enabled', null, []],
                ['publishedAt', null, ['widget' => 'single_text']],
                ['category', null, ['choice_label' => 'id']],
                ['users', Argument::cetera()],
            ])
        ;

        $this->defaultFormBuilder->build($this->metadataMock, $this->formBuilderMock, []);
    }

    public function testExcludesCommonFieldsLikeCreatedAtAndUpdatedAt(): void
    {
        $this->metadataMock->expects($this->once())->method('getClass')->with('model')->willReturn('AppBundle\Entity\Book');

        $this->entityManagerMock->expects($this->once())->method('getClassMetadata')->with('AppBundle\Entity\Book')->willReturn($this->classMetadataMock);

        $this->classMetadataMock->fieldNames = ['name', 'description', 'enabled', 'createdAt', 'updatedAt'];
        $this->classMetadataMock->expects($this->once())->method('isIdentifierNatural')->willReturn(true);
        $this->classMetadataMock->expects($this->once())->method('getAssociationMappings')->willReturn([]);
        $this->classMetadataMock
            ->expects($this->exactly(3))
            ->method('getTypeOfField')
            ->willReturnMap([
                ['name', Types::STRING],
                ['description', Types::TEXT],
                ['enabled', Types::BOOLEAN],
                ['createdAt', Types::DATETIME_MUTABLE],
                ['updatedAt', Types::DATETIME_MUTABLE],
            ])
        ;

        $this->formBuilderMock
            ->expects($this->exactly(3))
            ->method('add')
            ->willReturnMap([
                ['name', null, [], $this->formBuilderMock],
                ['description', null, [], $this->formBuilderMock],
                ['enabled', null, [], $this->formBuilderMock],
                ['createdAt', Argument::cetera(), $this->formBuilderMock],
                ['updatedAt', Argument::cetera(), $this->formBuilderMock],
                ['name', null, []],
                ['description', null, []],
                ['enabled', null, []],
                ['createdAt', Argument::cetera()],
                ['updatedAt', Argument::cetera()],
            ])
        ;

        $this->defaultFormBuilder->build($this->metadataMock, $this->formBuilderMock, []);
    }
}
