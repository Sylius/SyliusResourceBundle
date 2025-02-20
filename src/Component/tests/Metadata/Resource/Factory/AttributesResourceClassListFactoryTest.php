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

namespace Sylius\Resource\Tests\Metadata\Resource\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Resource\Factory\AttributesResourceClassListFactory;
use Sylius\Resource\Tests\Dummy\DummyResource;
use Sylius\Resource\Tests\Dummy\PullRequest;

final class AttributesResourceClassListFactoryTest extends TestCase
{
    public function testCreateAResourceClassListForResourcesWithAsResourceAttribute(): void
    {
        $attributesResourceNameCollectionFactory = new AttributesResourceClassListFactory(
            mapping: ['paths' => [dirname(__DIR__, 3) . '/Dummy']],
        );

        $collection = $attributesResourceNameCollectionFactory->create();

        $this->assertContains(DummyResource::class, $collection->getIterator());
        $this->assertNotContains(PullRequest::class, $collection->getIterator());
    }
}
