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
use Sylius\Bundle\ResourceBundle\Storage\CookieStorage;
use Sylius\Resource\Storage\StorageInterface;

final class CookieStorageTest extends TestCase
{
    private CookieStorage $storage;

    protected function setUp(): void
    {
        $this->storage = new CookieStorage();
    }

    public function testItImplementsStorageInterface(): void
    {
        $this->assertInstanceOf(StorageInterface::class, $this->storage);
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
            'buzz' => 'lightyear',
            'foo' => 'bar',
        ], $this->storage->all());
    }
}
