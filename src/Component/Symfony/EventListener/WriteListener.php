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

namespace Sylius\Component\Resource\Symfony\EventListener;

use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class WriteListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $contextInitiator,
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
            !($operation->canWrite() ?? true) ||
            $request->isMethodSafe() ||
            !$request->attributes->getBoolean('is_valid', true)
        ) {
            return;
        }

        switch ($request->getMethod()) {
            case 'PUT':
            case 'PATCH':
            case 'POST':
                $persistResult = $this->processor->process($controllerResult, $operation, $context);

                if (!$persistResult) {
                    return;
                }

                if ($persistResult instanceof Response) {
                    $event->setResponse($persistResult);

                    return;
                }

                $controllerResult = $persistResult;
                $event->setControllerResult($controllerResult);

                break;
            case 'DELETE':
                $this->processor->process($controllerResult, $operation, $context);
                $event->setControllerResult(null);

                break;
        }
    }
}
