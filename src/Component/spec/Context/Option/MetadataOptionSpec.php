<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Resource\Context\Option;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Metadata\MetadataInterface;

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
