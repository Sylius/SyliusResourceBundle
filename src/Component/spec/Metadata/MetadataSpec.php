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

namespace spec\Sylius\Component\Resource\Metadata;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\MetadataInterface as LegacyMetadataInterface;
use Sylius\Resource\Metadata\Metadata as NewMetadata;
use Sylius\Resource\Metadata\MetadataInterface;

final class MetadataSpec extends ObjectBehavior
{
    function it_implements_metadata_interface(): void
    {
        $this->shouldImplement(MetadataInterface::class);
    }

    function it_implements_legacy_metadata_interface(): void
    {
        $this->shouldImplement(LegacyMetadataInterface::class);
    }

    function it_should_be_an_alias_of_metadata(): void
    {
        $this->shouldBeAnInstanceOf(NewMetadata::class);
    }
}
