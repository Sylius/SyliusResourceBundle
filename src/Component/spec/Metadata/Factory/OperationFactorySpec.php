<?php

namespace spec\Sylius\Component\Resource\Metadata\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Factory\OperationFactory;

class OperationFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationFactory::class);
    }

    function it_creates_operations_and_merges_data(): void
    {
        $operation = $this->create(Create::class, ['criteria' => ['foo' => 'fighters']]);
        $operation->shouldHaveType(Create::class);
        $operation->getCriteria()->shouldReturn([
            'foo' => 'fighters',
        ]);
    }
}
