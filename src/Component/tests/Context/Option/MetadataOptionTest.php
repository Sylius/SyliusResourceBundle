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

namespace Sylius\Resource\Tests\Context\Option;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Metadata\MetadataInterface;

final class MetadataOptionTest extends TestCase
{
    private MetadataInterface|MockObject $metadata;

    private MetadataOption $metadataOption;

    protected function setUp(): void
    {
        $this->metadata = $this->createMock(MetadataInterface::class);

        $this->metadataOption = new MetadataOption($this->metadata);
    }

    /** @test */
    public function it_returns_request_configuration(): void
    {
        $this->assertEquals($this->metadata, $this->metadataOption->metadata());
    }
}
