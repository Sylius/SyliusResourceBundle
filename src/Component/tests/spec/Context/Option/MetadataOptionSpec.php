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

namespace spec\Sylius\Resource\Context\Option;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Metadata\MetadataInterface;

final class MetadataOptionSpec extends ObjectBehavior
{
    function let(MetadataInterface $metadata): void
    {
        $this->beConstructedWith($metadata);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(MetadataOption::class);
    }

    function it_returns_request_configuration(MetadataInterface $metadata): void
    {
        $this->metadata()->shouldReturn($metadata);
    }
}
