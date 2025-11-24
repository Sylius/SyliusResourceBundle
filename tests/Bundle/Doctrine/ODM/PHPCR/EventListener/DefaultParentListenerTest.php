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

use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use PHPCR\NodeInterface;
use PHPCR\SessionInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\DefaultParentListener;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

/**
 * @require Doctrine\ODM\PHPCR\DocumentManagerInterface
 */
final class DefaultParentListenerTest extends TestCase
{
    private DocumentManagerInterface|MockObject $documentManagerMock;

    private DefaultParentListener $defaultParentListener;

    private ResourceControllerEvent|MockObject $eventMock;

    private ClassMetadata|MockObject $documentMetadataMock;

    protected function setUp(): void
    {
        if (!interface_exists(DocumentManagerInterface::class)) {
            $this->markTestSkipped('Doctrine PHPCR ODM not installed');
        }

        $this->documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to');
        $this->eventMock = $this->createMock(ResourceControllerEvent::class);
        $this->documentMetadataMock = $this->createMock(ClassMetadata::class);
    }

    public function testThrowAnExceptionIfNoParentMappingExists(): void
    {
        $this->eventMock->expects($this->once())->method('getSubject')->willReturn(new \stdClass());

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(\stdClass::class)
            ->willReturn($this->documentMetadataMock)
        ;

        $this->documentMetadataMock->parentMapping = null;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(
            'A default parent path has been specified, but no parent mapping has been applied to document "stdClass"',
        );

        $this->defaultParentListener->onPreCreate($this->eventMock);
    }

    public function testThrowAnExceptionIfTheParentDoesNotExistAndAutocreateIsFalse(): void
    {
        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to', false);

        $this->eventMock->expects($this->once())->method('getSubject')->willReturn(new \stdClass());

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(\stdClass::class)
            ->willReturn($this->documentMetadataMock)
        ;

        $this->documentMetadataMock->parentMapping = 'parent';

        $this->documentManagerMock
            ->expects($this->once())
            ->method('find')
            ->with(null, '/path/to')
            ->willReturn(null)
        ;

        $this->expectException(\RuntimeException::class);

        $this->expectExceptionMessage(
            'Document at default parent path "/path/to" does not exist. `autocreate` was set to "false"',
        );
        $this->defaultParentListener->onPreCreate($this->eventMock);
    }

    public function testSetTheParentDocument(): void
    {
        $subjectDocument = new \stdClass();

        $parentDocument = new \stdClass();

        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(\stdClass::class)
            ->willReturn($this->documentMetadataMock)
        ;

        $this->documentMetadataMock->parentMapping = 'parent';

        $this->documentMetadataMock
            ->expects($this->once())
            ->method('getFieldValue')
            ->with($subjectDocument, 'parent')
            ->willReturn(null)
        ;

        $this->documentManagerMock
            ->expects($this->once())
            ->method('find')
            ->with(null, '/path/to')
            ->willReturn($parentDocument)
        ;

        $this->documentMetadataMock
            ->expects($this->once())
            ->method('setFieldValue')
            ->with($subjectDocument, 'parent', $parentDocument)
        ;

        $this->defaultParentListener->onPreCreate($this->eventMock);
    }

    public function testAutocreateAndSetTheParentDocument(): void
    {
        /** @var SessionInterface|MockObject $sessionMock */
        $sessionMock = $this->createMock(SessionInterface::class);
        /** @var NodeInterface|MockObject $nodeMock */
        $nodeMock = $this->createMock(NodeInterface::class);

        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to', true);

        $subjectDocument = new \stdClass();

        $parentDocument = new \stdClass();

        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(\stdClass::class)
            ->willReturn($this->documentMetadataMock)
        ;

        $this->documentMetadataMock->parentMapping = 'parent';

        $this->documentManagerMock
            ->expects($this->exactly(2))
            ->method('find')
            ->with(null, '/path/to')
            ->willReturn(null, $parentDocument)
        ;
        $this->documentManagerMock
            ->expects($this->once())
            ->method('getPhpcrSession')
            ->willReturn($sessionMock)
        ;

        $sessionMock->expects($this->once())->method('getRootNode')->willReturn($nodeMock);

        // we need to mock the behavior of the node helper
        // see: https://github.com/phpcr/phpcr-utils/issues/106
        $nodeMock
            ->expects($this->exactly(2))
            ->method('hasNode')
            ->with($this->anything())
            ->willReturn(true)
        ;
        $nodeMock
            ->expects($this->exactly(2))
            ->method('getNode')
            ->with($this->anything())
            ->willReturn($nodeMock)
        ;

        $this->documentMetadataMock
            ->expects($this->once())
            ->method('setFieldValue')
            ->with($subjectDocument, 'parent', $parentDocument)
        ;

        $this->defaultParentListener->onPreCreate($this->eventMock);
    }

    public function testSetTheParentDocumentIfForceIsTrueAndTheParentIsAlreadySet(): void
    {
        $this->defaultParentListener = new DefaultParentListener($this->documentManagerMock, '/path/to', false, true);

        $subjectDocument = new \stdClass();

        $parentDocument = new \stdClass();

        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(\stdClass::class)
            ->willReturn($this->documentMetadataMock)
        ;

        $this->documentMetadataMock
            ->expects($this->never())
            ->method('getFieldValue')
            ->with($subjectDocument, 'parent')
        ;
        $this->documentMetadataMock
            ->expects($this->once())
            ->method('setFieldValue')
            ->with($subjectDocument, 'parent', $parentDocument)
        ;
        $this->documentMetadataMock->parentMapping = 'parent';

        $this->documentManagerMock
            ->expects($this->once())
            ->method('find')
            ->with(null, '/path/to')
            ->willReturn($parentDocument)
        ;

        $this->defaultParentListener->onPreCreate($this->eventMock);
    }

    public function testReturnEarlyIfForceIsFalseAndSubjectAlreadyHasAParent(): void
    {
        $subjectDocument = new \stdClass();

        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($subjectDocument);

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(\stdClass::class)
            ->willReturn($this->documentMetadataMock)
        ;

        $this->documentMetadataMock->parentMapping = 'parent';
        $this->documentMetadataMock
            ->expects($this->once())
            ->method('getFieldValue')
            ->with($subjectDocument, 'parent')
            ->willReturn(new \stdClass())
        ;

        $this->documentManagerMock->expects($this->never())->method('find')->with(null, '/path/to');

        $this->defaultParentListener->onPreCreate($this->eventMock);
    }
}
