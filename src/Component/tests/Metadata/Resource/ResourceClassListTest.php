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
        $list = new ResourceClassList();

        $this->assertInstanceOf(\IteratorAggregate::class, $list);
    }

    public function testItIsCountable(): void
    {
        $list = new ResourceClassList();

        $this->assertInstanceOf(\Countable::class, $list);
    }

    public function testItIsAListOfResourceClassNames(): void
    {
        $list = new ResourceClassList(['first_resource', 'second_resource']);

        $this->assertCount(2, $list);
        $this->assertEquals('first_resource', $list->getIterator()[0]);
        $this->assertEquals('second_resource', $list->getIterator()[1]);
    }
}
