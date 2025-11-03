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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\NameFilterListener;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

/**
 * @require Doctrine\ODM\PHPCR\DocumentManagerInterface
 */
final class NameFilterListenerTest extends TestCase
{
    private DocumentManagerInterface|MockObject $documentManagerMock;

    private NameFilterListener $nameFilterListener;

    private ResourceControllerEvent|MockObject $eventMock;

    private ClassMetadata|MockObject $metadataMock;

    private \stdClass $document;

    protected function setUp(): void
    {
        if (!interface_exists(DocumentManagerInterface::class)) {
            $this->markTestSkipped('Doctrine PHPCR ODM not installed');
        }

        $this->documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $this->nameFilterListener = new NameFilterListener($this->documentManagerMock);
        $this->eventMock = $this->createMock(ResourceControllerEvent::class);
        $this->metadataMock = $this->createMock(ClassMetadata::class);
        $this->document = new \stdClass();
    }

    public function testThrowsAnExceptionIfNodenameIsNotMapped(): void
    {
        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($this->document);

        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($this->metadataMock);

        $this->metadataMock->nodename = null;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('In order to use the node name filter on "stdClass" it is necessary to map a field as the "nodename"');

        $this->nameFilterListener->onEvent($this->eventMock);
    }

    public function testCleanTheName(): void
    {
        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($this->document);

        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($this->metadataMock);

        $this->metadataMock->nodename = 'foobar';
        $this->metadataMock->expects($this->once())->method('getFieldValue')->with($this->document, 'foobar')->willReturn('Hello//Foo');
        $this->metadataMock->expects($this->once())->method('setFieldValue')->with($this->document, 'foobar', 'Hello  Foo');

        $this->nameFilterListener->onEvent($this->eventMock);
    }

    public function testUseTheGivenReplacementChar(): void
    {
        $this->nameFilterListener = new NameFilterListener($this->documentManagerMock, '_');

        $this->eventMock->expects($this->once())->method('getSubject')->willReturn($this->document);

        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($this->metadataMock);

        $this->metadataMock->nodename = 'foobar';
        $this->metadataMock->expects($this->once())->method('getFieldValue')->with($this->document, 'foobar')->willReturn('Hello//Foo');
        $this->metadataMock->expects($this->once())->method('setFieldValue')->with($this->document, 'foobar', 'Hello__Foo');

        $this->nameFilterListener->onEvent($this->eventMock);
    }
}
