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
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\NameResolverListener;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use PHPCR\NodeInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

/**
 * @require Doctrine\ODM\PHPCR\DocumentManagerInterface
 */
final class NameResolverListenerTest extends TestCase
{
    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManagerInterface|MockObject
     */
    private MockObject $documentManagerMock;
    private NameResolverListener $nameResolverListener;
    protected function setUp(): void
    {
        if (!interface_exists(DocumentManagerInterface::class)) {
            $this->markTestSkipped('Doctrine PHPCR ODM not installed');
        }

        $this->documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $this->nameResolverListener = new NameResolverListener($this->documentManagerMock);
    }

    function testThrowsAnExceptionWhenTheGeneratorTypeIsNotParent(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $metadataMock */
        $metadataMock = $this->createMock(ClassMetadata::class);
        $document = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($document);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($metadataMock);
        $metadataMock->idGenerator = 'foo';
        $this->expectException(\RuntimeException::class);
        $this->nameResolverListener->expectExceptionMessage('Document of class "stdClass" must be using the GENERATOR_TYPE_PARENT identificatio strategy (value 3), it is current using "foo" (this may be an automatic configuration: be sure to map both the `nodename` and the `parentDocument`).');
        $this->nameResolverListener->onEvent($eventMock);
    }

    function testRetainTheOriginalNameWhenNoConflictExists(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $metadataMock */
        $metadataMock = $this->createMock(ClassMetadata::class);
        /** @var \PHPCR\NodeInterface|MockObject $nodeMock */
        $nodeMock = $this->createMock(NodeInterface::class);
        $document = new \stdClass();
        $parentDocument = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($document);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($metadataMock);
        $metadataMock->idGenerator = ClassMetadata::GENERATOR_TYPE_PARENT;
        $metadataMock->nodename = 'title';
        $metadataMock->parentMapping = 'parent';
        $metadataMock->expects($this->exactly(2))->method('getFieldValue')->willReturnMap([[$document, 'parent', $parentDocument], [$document, 'title', 'Hello World']]);
        $nodeMock->expects($this->once())->method('getPath')->willReturn('/path/to');
        $metadataMock->expects($this->once())->method('getFieldValue')->with($document, 'title')->willReturn('Hello World');
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to/Hello World')->willReturn(null);
        $metadataMock->expects($this->once())->method('setFieldValue')->with($document, 'title', 'Hello World');
        $this->nameResolverListener->onEvent($eventMock);
    }

    function testAutoIncrementTheNameIfAConflictExists(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $metadataMock */
        $metadataMock = $this->createMock(ClassMetadata::class);
        /** @var \PHPCR\NodeInterface|MockObject $nodeMock */
        $nodeMock = $this->createMock(NodeInterface::class);
        $document = new \stdClass();
        $parentDocument = new \stdClass();
        $existingDocument = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($document);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($metadataMock);
        $metadataMock->idGenerator = ClassMetadata::GENERATOR_TYPE_PARENT;
        $metadataMock->nodename = 'title';
        $metadataMock->parentMapping = 'parent';
        $metadataMock->expects($this->exactly(2))->method('getFieldValue')->willReturnMap([[$document, 'parent', $parentDocument], [$document, 'title', 'Hello World']]);
        $nodeMock->expects($this->once())->method('getPath')->willReturn('/path/to');
        $metadataMock->expects($this->once())->method('getFieldValue')->with($document, 'title')->willReturn('Hello World');
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to/Hello World')->willReturn(
            $existingDocument,
        );
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to/Hello World-1')->willReturn(
            $existingDocument,
        );
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to/Hello World-2')->willReturn(
            $existingDocument,
        );
        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to/Hello World-3')->willReturn(
            null,
        );
        $metadataMock->expects($this->once())->method('setFieldValue')->with($document, 'title', 'Hello World-3');
        $this->nameResolverListener->onEvent($eventMock);
    }
}
