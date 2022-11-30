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

namespace spec\Sylius\Component\Resource\Metadata;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Update;

final class ResourceSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Resource::class);
    }

    function it_has_no_alias_by_default(): void
    {
        $this->getAlias()->shouldReturn(null);
    }

    function it_could_have_an_alias(): void
    {
        $this->withAlias('app.book')
            ->getAlias()
            ->shouldReturn('app.book');
    }

    function it_has_no_operations_by_default(): void
    {
        $this->getOperations()->shouldReturn(null);
    }

    function it_could_have_operations(): void
    {
        $operations = new Operations();

        $this->withOperations($operations)
            ->getOperations()
            ->shouldReturn($operations);
    }

    function it_can_be_constructed_with_an_alias(): void
    {
        $this->beConstructedWith('app.book');

        $this->getAlias()->shouldReturn('app.book');
    }

    function it_can_be_constructed_with_operations(): void
    {
        $operations = [new Create(), new Update()];

        $this->beConstructedWith(null, $operations);

        $this->getOperations()->shouldHaveCount(2);
    }
}
