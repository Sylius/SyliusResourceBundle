<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Provider;

use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;

interface StateMachineProviderInterface
{
    public function getStateMachine(): StateMachineInterface;
}
