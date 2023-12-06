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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Context\Option\MetadataOption;

final class MetadataOptionTest extends TestCase
{
    use ProphecyTrait;

    private MetadataInterface|ObjectProphecy $metadata;

    private MetadataOption $metadataOption;

    protected function setUp(): void
    {
        $this->metadata = $this->prophesize(MetadataInterface::class);

        $this->metadataOption = new MetadataOption($this->metadata->reveal());
    }

    /** @test */
    public function it_returns_request_configuration(): void
    {
        $this->assertEquals($this->metadata->reveal(), $this->metadataOption->metadata());
    }
}
