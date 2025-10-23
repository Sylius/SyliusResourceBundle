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

namespace Sylius\Bundle\ResourceBundle\Tests\Doctrine\ODM\PHPCR\EventListener;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\DefaultParentListener;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use PHPCR\NodeInterface;
use PHPCR\SessionInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

/**
 * @require Doctrine\ODM\PHPCR\DocumentManagerInterface
 */
final class DefaultParentListenerTest extends TestCase
{
    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManagerInterface|MockObject
     */
    private MockObject $documentManagerMock;
    private DefaultParentListener $defaultParentListener;
    protected function setUp(): void
    {
        if (!interface_exists(DocumentManagerInterface::class)) {
            $this->markTestSkipped('Doctrine PHPCR ODM not installed');
        }

        $this->documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to');
    }

    function testThrowAnExceptionIfNoParentMappingExists(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $documentMetadataMock */
        $documentMetadataMock = $this->createMock(ClassMetadata::class);
        $eventMock->expects($this->once())->method('getSubject')->willReturn(new \stdClass());
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with(\stdClass::class)->willReturn(
            $documentMetadataMock,
        );
        $documentMetadataMock->parentMapping = null;
        $this->expectException(\RuntimeException::class);
        $this->defaultParentListener->expectExceptionMessage('A default parent path has been specified, but no parent mapping has been applied to document "stdClass"');
        $this->defaultParentListener->onPreCreate($eventMock);
    }

    function testThrowAnExceptionIfTheParentDoesNotExistAndAutocreateIsFalse(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $documentMetadataMock */
        $documentMetadataMock = $this->createMock(ClassMetadata::class);
        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to', false);
        $eventMock->expects($this->once())->method('getSubject')->willReturn(new \stdClass());
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with(\stdClass::class)->willReturn(
            $documentMetadataMock,
        );
        $documentMetadataMock->parentMapping = 'parent';
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to')->willReturn(null);
        $this->expectException(\RuntimeException::class);
        $this->defaultParentListener->expectExceptionMessage('Document at default parent path "/path/to" does not exist. `autocreate` was set to "false"');
        $this->defaultParentListener->onPreCreate($eventMock);
    }

    function testSetTheParentDocument(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $documentMetadataMock */
        $documentMetadataMock = $this->createMock(ClassMetadata::class);
        $subjectDocument = new \stdClass();
        $parentDocument = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with(\stdClass::class)->willReturn(
            $documentMetadataMock,
        );
        $documentMetadataMock->parentMapping = 'parent';
        $documentMetadataMock->expects($this->once())->method('getFieldValue')->with($subjectDocument, 'parent')->willReturn(null);
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to')->willReturn($parentDocument);
        $documentMetadataMock->expects($this->once())->method('setFieldValue')->with($subjectDocument, 'parent', $parentDocument);
        $this->defaultParentListener->onPreCreate($eventMock);
    }

    function testAutocreateAndSetTheParentDocument(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $documentMetadataMock */
        $documentMetadataMock = $this->createMock(ClassMetadata::class);
        /** @var \PHPCR\SessionInterface|MockObject $sessionMock */
        $sessionMock = $this->createMock(SessionInterface::class);
        /** @var \PHPCR\NodeInterface|MockObject $nodeMock */
        $nodeMock = $this->createMock(NodeInterface::class);
        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to', true);
        $subjectDocument = new \stdClass();
        $parentDocument = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with(\stdClass::class)->willReturn(
            $documentMetadataMock,
        );
        $documentMetadataMock->parentMapping = 'parent';
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to')->willReturn(null, $parentDocument);
        $this->documentManagerMock->expects($this->once())->method('getPhpcrSession')->willReturn($sessionMock);
        $sessionMock->expects($this->once())->method('getRootNode')->willReturn($nodeMock);
        // we need to mock the behavior of the node helper
        // see: https://github.com/phpcr/phpcr-utils/issues/106
        $nodeMock->expects($this->once())->method('hasNode')->with($this->any())->willReturn(true);
        $nodeMock->expects($this->once())->method('getNode')->with($this->any())
            ->willReturn($nodeMock)
            ->shouldBeCalledTimes(2)
        ;
        $documentMetadataMock->setFieldValue($subjectDocument, 'parent', $parentDocument);
        $this->defaultParentListener->onPreCreate($eventMock);
    }

    function testSetTheParentDocumentIfForceIsTrueAndTheParentIsAlreadySet(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $documentMetadataMock */
        $documentMetadataMock = $this->createMock(ClassMetadata::class);
        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to', false, true);
        $subjectDocument = new \stdClass();
        $parentDocument = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with(\stdClass::class)->willReturn(
            $documentMetadataMock,
        );
        $documentMetadataMock->expects($this->never())->method('getFieldValue')->with($subjectDocument, 'parent');
        $documentMetadataMock->expects($this->once())->method('setFieldValue')->with($subjectDocument, 'parent', $parentDocument);
        $documentMetadataMock->parentMapping = 'parent';
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to')->willReturn($parentDocument);
        $this->defaultParentListener->onPreCreate($eventMock);
    }

    function testReturnEarlyIfForceIsFalseAndSubjectAlreadyHasAParent(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $documentMetadataMock */
        $documentMetadataMock = $this->createMock(ClassMetadata::class);
        $subjectDocument = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with(\stdClass::class)->willReturn(
            $documentMetadataMock,
        );
        $documentMetadataMock->parentMapping = 'parent';
        $documentMetadataMock->expects($this->once())->method('getFieldValue')->with($subjectDocument, 'parent')
            ->willReturn(new \stdClass())
        ;
        $this->documentManagerMock->expects($this->never())->method('find')->with(null, '/path/to');
        $this->defaultParentListener->onPreCreate($eventMock);
    }
}
