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

namespace spec\Sylius\Bundle\ResourceBundle\Provider;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Provider\StateMachineProviderInterface;

final class StateMachineProviderSpec extends ObjectBehavior
{
    function it_implements_state_machine_provider_interface(): void
    {
        $this->shouldImplement(StateMachineProviderInterface::class);
    }

    function it_provides_state_machine_if_defined(StateMachineInterface $stateMachine): void
    {
        $this->beConstructedWith($stateMachine);

        $this->getStateMachine()->shouldReturn($stateMachine);
    }

    function it_throws_an_exception_if_state_machine_is_not_defined(): void
    {
        $this->beConstructedWith(null);

        $this
            ->shouldThrow(new \LogicException(
                'You can not use the "state-machine" if Winzou State Machine Bundle is not available. Try running "composer require winzou/state-machine-bundle".'
            ))
            ->during('getStateMachine')
        ;
    }
}
