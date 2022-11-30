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
use Sylius\Component\Resource\Metadata\Update;

final class OperationsSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Operations::class);
    }

    function it_is_countable(): void
    {
        $this->shouldImplement(\Countable::class);
    }

    function it_is_an_iterator(): void
    {
        $this->shouldImplement(\IteratorAggregate::class);
    }

    function it_adds_operations(): void
    {
        $this->add('create', new Create());

        $this->has('create')->shouldReturn(true);
    }

    function it_removes_operations(): void
    {
        $this->add('create', new Create());

        $this->remove('create');

        $this->has('create')->shouldReturn(false);
    }

    function it_merges_operations(): void
    {
        $this->add('create', new Create());
        $this->add('create', new Create(name: 'new_name'));

        $this->count()->shouldReturn(1);
        $this->has('create')->shouldReturn(true);
        $this->getIterator()->current()->getName()->shouldReturn('new_name');
    }

    function it_throws_a_runtime_exception_when_removing_not_found_operation(): void
    {
        $this->shouldThrow(\RuntimeException::class)->during('remove', ['not_found_operation']);
    }

    function it_returns_operations_count(): void
    {
        $this->add('create', new Create());
        $this->add('update', new Update());

        $this->count()->shouldReturn(2);
    }

    function it_sorts_operations(): void
    {
        $this->add('create', new Create());
        $this->add('update', new Update(priority: 100));

        $this->sort();

        $this->getIterator()->key()->shouldReturn('update');
    }
}
