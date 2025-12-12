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

namespace Sylius\Component\Resource\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\MetadataInterface as LegacyMetadataInterface;
use Sylius\Resource\Metadata\Metadata as NewMetadata;
use Sylius\Resource\Metadata\MetadataInterface;

final class MetadataTest extends TestCase
{
    private Metadata $metadata;

    protected function setUp(): void
    {
        $this->metadata = new Metadata('sylius_product', 'app', 'product', 'App\Entity\Product');
    }

    public function testItImplementsMetadataInterface(): void
    {
        $this->assertInstanceOf(MetadataInterface::class, $this->metadata);
    }

    public function testItImplementsLegacyMetadataInterface(): void
    {
        $this->assertInstanceOf(LegacyMetadataInterface::class, $this->metadata);
    }

    public function testItShouldBeAnAliasOfMetadata(): void
    {
        $this->assertInstanceOf(NewMetadata::class, $this->metadata);
    }
}
