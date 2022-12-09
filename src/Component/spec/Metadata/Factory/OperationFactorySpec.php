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

namespace spec\Sylius\Component\Resource\Metadata\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Factory\OperationFactory;

final class OperationFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationFactory::class);
    }

    function it_creates_operations_and_merges_attributes(): void
    {
        $operation = $this->create(Create::class, []);
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('create');
        $operation->getMethods()->shouldReturn(['GET', 'POST']);
        $operation->getPath()->shouldReturn(null);
    }

    function it_merges_attributes(): void
    {
        $operation = $this->create(Create::class, [
            'name' => 'register',
            'criteria' => ['foo' => 'fighters'],
        ]);
        $operation->shouldHaveType(Create::class);
        $operation->getName()->shouldReturn('register');
        $operation->getMethods()->shouldReturn(['GET', 'POST']);
    }
}
