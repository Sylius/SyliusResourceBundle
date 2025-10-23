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
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\EventListener\NameFilterListener;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

/**
 * @require Doctrine\ODM\PHPCR\DocumentManagerInterface
 */
final class NameFilterListenerTest extends TestCase
{
    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManagerInterface|MockObject
     */
    private MockObject $documentManagerMock;
    private NameFilterListener $nameFilterListener;
    protected function setUp(): void
    {
        if (!interface_exists(DocumentManagerInterface::class)) {
            $this->markTestSkipped('Doctrine PHPCR ODM not installed');
        }

        $this->documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $this->nameFilterListener = new NameFilterListener($this->documentManagerMock);
    }

    function testThrowsAnExceptionIfNodenameIsNotMapped(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $metadataMock */
        $metadataMock = $this->createMock(ClassMetadata::class);
        $document = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($document);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($metadataMock);
        $metadataMock->nodename = null;
        $this->expectException(\RuntimeException::class);
        $this->nameFilterListener->expectExceptionMessage('In order to use the node name filter on "stdClass" it is necessary to map a field as the "nodename"');
        $this->nameFilterListener->onEvent($eventMock);
    }

    function testCleanTheName(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $metadataMock */
        $metadataMock = $this->createMock(ClassMetadata::class);
        $document = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($document);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($metadataMock);
        $metadataMock->nodename = 'foobar';
        $metadataMock->expects($this->once())->method('getFieldValue')->with($document, 'foobar')->willReturn('Hello//Foo');
        $metadataMock->expects($this->once())->method('setFieldValue')->with($document, 'foobar', 'Hello  Foo');
        $this->nameFilterListener->onEvent($eventMock);
    }

    function testUseTheGivenReplacementChar(): void
    {
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var \Doctrine\ODM\PHPCR\Mapping\ClassMetadata|MockObject $metadataMock */
        $metadataMock = $this->createMock(ClassMetadata::class);
        $this->nameFilterListener = new NameFilterListener($this->documentManagerMock, '_');
        $document = new \stdClass();
        $eventMock->expects($this->once())->method('getSubject')->willReturn($document);
        $this->documentManagerMock->expects($this->once())->method('getClassMetadata')->with('stdClass')->willReturn($metadataMock);
        $metadataMock->nodename = 'foobar';
        $metadataMock->expects($this->once())->method('getFieldValue')->with($document, 'foobar')->willReturn('Hello//Foo');
        $metadataMock->expects($this->once())->method('setFieldValue')->with($document, 'foobar', 'Hello__Foo');
        $this->nameFilterListener->onEvent($eventMock);
    }
}
