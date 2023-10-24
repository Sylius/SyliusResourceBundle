<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Symfony\EventListener;

use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Webmozart\Assert\Assert;

final class RespondListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $contextInitiator,
        private ResponderInterface $responder,
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
            $controllerResult instanceof Response
        ) {
            return;
        }

        $response = $this->responder->respond($controllerResult, $operation, $context);
        Assert::isInstanceOf($response, Response::class);

        $event->setResponse($response);
    }
}
