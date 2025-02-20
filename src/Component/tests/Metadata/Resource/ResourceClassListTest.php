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

namespace Sylius\Resource\Tests\Metadata\Resource;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Resource\ResourceClassList;

final class ResourceClassListTest extends TestCase
{
    public function testItIsAnIteratorAggregate(): void
    {
        $collection = new ResourceClassList();

        $this->assertInstanceOf(\IteratorAggregate::class, $collection);
    }

    public function testItIsCountable(): void
    {
        $collection = new ResourceClassList();

        $this->assertInstanceOf(\Countable::class, $collection);
    }

    public function testItIsAListOfResourceClassNames(): void
    {
        $collection = new ResourceClassList(['first_resource', 'second_resource']);

        $this->assertCount(2, $collection);
        $this->assertEquals('first_resource', $collection->getIterator()[0]);
        $this->assertEquals('second_resource', $collection->getIterator()[1]);
    }
}
