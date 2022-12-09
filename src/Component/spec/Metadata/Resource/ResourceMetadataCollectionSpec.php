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

namespace spec\Sylius\Component\Resource\Metadata\Resource;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Tests\Dummy\DummyResource;

final class ResourceMetadataCollectionSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(DummyResource::class);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourceMetadataCollection::class);
    }
}
