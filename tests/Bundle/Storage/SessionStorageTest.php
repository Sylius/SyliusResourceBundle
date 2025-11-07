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

namespace Sylius\Bundle\ResourceBundle\Tests\Bundle\Storage;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Storage\SessionStorage;
use Sylius\Resource\Exception\StorageUnavailableException;
use Sylius\Resource\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

final class SessionStorageTest extends TestCase
{
    private SessionStorage $storage;

    protected function setUp(): void
    {
        if (method_exists(RequestStack::class, 'getSession')) {
            $requestStack = $this->createMock(RequestStack::class);
            $requestStack
                ->method('getSession')
                ->willReturn(new Session(new MockArraySessionStorage()));

            $this->storage = new SessionStorage($requestStack);

            return;
        }

        $this->storage = new SessionStorage(new Session(new MockArraySessionStorage()));
    }

    public function testItImplementsStorageInterface(): void
    {
        $this->assertInstanceOf(StorageInterface::class, $this->storage);
    }

    /**
     * @dataProvider storageMethodsDataProvider
     */
    public function testItThrowsStorageUnavailableExceptionWhenSessionNotAvailable(callable $method): void
    {
        if (!method_exists(RequestStack::class, 'getSession')) {
            $this->markTestSkipped('RequestStack::getSession() method does not exist in this Symfony version.');
        }

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->method('getSession')
            ->willThrowException(new SessionNotFoundException());

        $storage = new SessionStorage($requestStack);

        $this->expectException(StorageUnavailableException::class);
        $method($storage);
    }

    /**
     * @return iterable<string, array{callable(SessionStorage): mixed}>
     */
    public static function storageMethodsDataProvider(): iterable
    {
        yield 'has' => [fn (SessionStorage $storage) => $storage->has('name')];
        yield 'get' => [fn (SessionStorage $storage) => $storage->get('name')];
        yield 'set' => [fn (SessionStorage $storage) => $storage->set('name', 'value')];
        yield 'remove' => [fn (SessionStorage $storage) => $storage->remove('name')];
        yield 'all' => [fn (SessionStorage $storage) => $storage->all()];
    }

    public function testItDoesNotHaveValueWhenNotSetPreviously(): void
    {
        $this->assertFalse($this->storage->has('name'));
        $this->assertNull($this->storage->get('name'));
    }

    public function testItCanSetAndGetValue(): void
    {
        $this->storage->set('name', 'value');

        $this->assertTrue($this->storage->has('name'));
        $this->assertSame('value', $this->storage->get('name'));
    }

    public function testItCanRemoveValue(): void
    {
        $this->storage->set('name', 'value');

        $this->storage->remove('name');

        $this->assertFalse($this->storage->has('name'));
        $this->assertNull($this->storage->get('name'));
    }

    public function testItReturnsDefaultValueWhenKeyNotFound(): void
    {
        $this->assertSame('default', $this->storage->get('name', 'default'));
    }

    public function testItReturnsAllStoredValues(): void
    {
        $this->storage->set('foo', 'bar');
        $this->storage->set('buzz', 'lightyear');

        $this->assertSame([
            'foo' => 'bar',
            'buzz' => 'lightyear',
        ], $this->storage->all());
    }
}
