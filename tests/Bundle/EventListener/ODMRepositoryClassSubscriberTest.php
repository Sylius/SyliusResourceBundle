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

namespace Sylius\Bundle\ResourceBundle\Tests\Bundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\EventListener\ODMRepositoryClassSubscriber;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;

final class ODMRepositoryClassSubscriberTest extends TestCase
{
    /** @var RegistryInterface&MockObject */
    private RegistryInterface $registry;

    private ODMRepositoryClassSubscriber $subscriber;

    protected function setUp(): void
    {
        if (!class_exists(Events::class)) {
            $this->markTestSkipped('Doctrine MongoDB ODM is not installed.');
        }

        $this->registry = $this->createMock(RegistryInterface::class);
        $this->subscriber = new ODMRepositoryClassSubscriber($this->registry);
    }

    public function testItImplementsEventSubscriberInterface(): void
    {
        $this->assertInstanceOf(EventSubscriber::class, $this->subscriber);
    }

    public function testItSubscribesToLoadClassMetadataEvent(): void
    {
        $this->assertSame([Events::loadClassMetadata], $this->subscriber->getSubscribedEvents());
    }

    public function testItSetsCustomRepositoryClassWhenConfigured(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata
            ->expects($this->once())
            ->method('hasClass')
            ->with('repository')
            ->willReturn(true);
        $metadata
            ->expects($this->once())
            ->method('getClass')
            ->with('repository')
            ->willReturn('FooRepository');

        $this->registry
            ->expects($this->once())
            ->method('getByClass')
            ->with('Foo')
            ->willReturn($metadata);

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata
            ->expects($this->once())
            ->method('getName')
            ->willReturn('Foo');
        $classMetadata
            ->expects($this->once())
            ->method('setCustomRepositoryClass')
            ->with('FooRepository');

        $documentManager = $this->createMock(DocumentManager::class);
        $event = new LoadClassMetadataEventArgs($classMetadata, $documentManager);

        $this->subscriber->loadClassMetadata($event);
    }

    public function testItDoesNotSetCustomRepositoryClassWhenNotConfigured(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata
            ->expects($this->once())
            ->method('hasClass')
            ->with('repository')
            ->willReturn(false);

        $this->registry
            ->expects($this->once())
            ->method('getByClass')
            ->with('Foo')
            ->willReturn($metadata);

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata
            ->expects($this->once())
            ->method('getName')
            ->willReturn('Foo');
        $classMetadata
            ->expects($this->never())
            ->method('setCustomRepositoryClass');

        $documentManager = $this->createMock(DocumentManager::class);
        $event = new LoadClassMetadataEventArgs($classMetadata, $documentManager);

        $this->subscriber->loadClassMetadata($event);
    }

    public function testItDoesNotSetCustomRepositoryClassWhenClassNotInRegistry(): void
    {
        $this->registry
            ->expects($this->once())
            ->method('getByClass')
            ->with('Foo')
            ->willThrowException(new \InvalidArgumentException());

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata
            ->expects($this->once())
            ->method('getName')
            ->willReturn('Foo');
        $classMetadata
            ->expects($this->never())
            ->method('setCustomRepositoryClass');

        $documentManager = $this->createMock(DocumentManager::class);
        $event = new LoadClassMetadataEventArgs($classMetadata, $documentManager);

        $this->subscriber->loadClassMetadata($event);
    }
}
