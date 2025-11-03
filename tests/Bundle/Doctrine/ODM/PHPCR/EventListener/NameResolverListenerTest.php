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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\NameResolverListener;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

/**
 * @require Doctrine\ODM\PHPCR\DocumentManagerInterface
 */
final class NameResolverListenerTest extends TestCase
{
    private DocumentManagerInterface|MockObject $documentManagerMock;

    private NameResolverListener $nameResolverListener;

    private ResourceControllerEvent|MockObject $eventMock;

    private ClassMetadata|MockObject $metadataMock;

    private NodeInterface|MockObject $nodeMock;

    private \stdClass $document;

    private \stdClass $parentDocument;

    protected function setUp(): void
    {
        if (!interface_exists(DocumentManagerInterface::class)) {
            $this->markTestSkipped('Doctrine PHPCR ODM not installed');
        }

        $this->documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $this->nameResolverListener = new NameResolverListener($this->documentManagerMock);
        $this->eventMock = $this->createMock(ResourceControllerEvent::class);
        $this->metadataMock = $this->createMock(ClassMetadata::class);
        $this->nodeMock = $this->createMock(NodeInterface::class);
        $this->document = new \stdClass();
        $this->parentDocument = new \stdClass();
    }

    public function testThrowsAnExceptionWhenTheGeneratorTypeIsNotParent(): void
    {
        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($this->document);

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with('stdClass')
            ->willReturn($this->metadataMock)
        ;

        $this->metadataMock->idGenerator = 1;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Document of class "stdClass" must be using the GENERATOR_TYPE_PARENT identificatio strategy (value 3), it is current using "1" (this may be an automatic configuration: be sure to map both the `nodename` and the `parentDocument`).');

        $this->nameResolverListener->onEvent($this->eventMock);
    }

    public function testRetainTheOriginalNameWhenNoConflictExists(): void
    {
        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($this->document);

        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($this->metadataMock);

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getNodeForDocument')
            ->with($this->parentDocument)
            ->willReturn($this->nodeMock)
        ;

        $this->metadataMock->idGenerator = ClassMetadata::GENERATOR_TYPE_PARENT;
        $this->metadataMock->nodename = 'title';
        $this->metadataMock->parentMapping = 'parent';
        $this->metadataMock
            ->expects($this->exactly(2))
            ->method('getFieldValue')
            ->willReturnMap([
                [$this->document, 'parent', $this->parentDocument],
                [$this->document, 'title', 'Hello World'],
            ])
        ;

        $this->nodeMock->expects($this->once())->method('getPath')->willReturn('/path/to');

        $this->documentManagerMock->expects($this->once())->method('find')->with(null, '/path/to/Hello World')->willReturn(null);

        $this->metadataMock->expects($this->once())->method('setFieldValue')->with($this->document, 'title', 'Hello World');

        $this->nameResolverListener->onEvent($this->eventMock);
    }

    public function testAutoIncrementTheNameIfAConflictExists(): void
    {
        $existingDocument = new \stdClass();

        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($this->document);

        $this->documentManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with('stdClass')
            ->willReturn($this->metadataMock)
        ;

        $this->metadataMock->idGenerator = ClassMetadata::GENERATOR_TYPE_PARENT;
        $this->metadataMock->nodename = 'title';
        $this->metadataMock->parentMapping = 'parent';
        $this->metadataMock
            ->expects($this->exactly(2))
            ->method('getFieldValue')
            ->willReturnMap([
                [$this->document, 'parent', $this->parentDocument],
                [$this->document, 'title', 'Hello World'],
            ])
        ;

        $this->documentManagerMock->expects($this->once())->method('getNodeForDocument')->with($this->parentDocument)->willReturn($this->nodeMock);

        $this->nodeMock->expects($this->once())->method('getPath')->willReturn('/path/to');

        $this->documentManagerMock
            ->expects($this->exactly(4))
            ->method('find')
            ->willReturnMap([
                [null, '/path/to/Hello World', $existingDocument],
                [null, '/path/to/Hello World-1', $existingDocument],
                [null, '/path/to/Hello World-2', $existingDocument],
                [null, '/path/to/Hello World-3', null],
            ])
        ;

        $this->metadataMock->expects($this->once())->method('setFieldValue')->with($this->document, 'title', 'Hello World-3');

        $this->nameResolverListener->onEvent($this->eventMock);
    }
}
