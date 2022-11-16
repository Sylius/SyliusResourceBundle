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

namespace Sylius\Component\Resource\Symfony\State;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProcessorInterface;

final class ApplyStateMachineProcessor implements ProcessorInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
        private ?ProcessorInterface $writeProcessor = null
    ) {
    }

    public function process(mixed $data, Operation $operation, RequestConfiguration $configuration): void
    {
        if ($this->stateMachine->can($configuration, $data)) {
            $this->stateMachine->apply($configuration, $data);
        }

        if (null === $this->writeProcessor) {
            return;
        }

        $this->writeProcessor->process($data, $operation, $configuration);
    }
}
