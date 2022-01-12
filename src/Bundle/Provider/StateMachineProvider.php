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

namespace Sylius\Bundle\ResourceBundle\Provider;

use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;

final class StateMachineProvider implements StateMachineProviderInterface
{
    private ?StateMachineInterface $stateMachine;

    public function __construct(?StateMachineInterface $stateMachine = null)
    {
        $this->stateMachine = $stateMachine;
    }

    public function getStateMachine(): StateMachineInterface
    {
        if (null === $this->stateMachine) {
            throw new \LogicException('You can not use the "state-machine" if Winzou State Machine Bundle is not available. Try running "composer require winzou/state-machine-bundle".');
        }

        return $this->stateMachine;
    }
}
