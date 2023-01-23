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

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Sylius\Component\Resource\Context\Initiator\RequestContextInitiator;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Component\Resource\State\ProcessorInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class WriteListener
{
    public function __construct(
        private HttpOperationInitiator $operationInitiator,
        private RequestContextInitiator $contextInitiator,
        private ProcessorInterface $processor,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $context = $this->contextInitiator->initializeContext($request);
        $operation = $this->operationInitiator->initializeOperation($request);

        $controllerResult = $event->getControllerResult();

        if (
            null === $operation ||
            !($operation->canWrite() ?? true)
        ) {
            return;
        }

        switch ($request->getMethod()) {
            case 'PUT':
            case 'PATCH':
            case 'POST':
                $persistResult = $this->processor->process($controllerResult, $operation, $context);

                if ($persistResult) {
                    $controllerResult = $persistResult;
                    $event->setControllerResult($controllerResult);
                }

                break;
            case 'DELETE':
                $this->processor->process($controllerResult, $operation, $context);
                $event->setControllerResult(null);

                break;
        }
    }
}
