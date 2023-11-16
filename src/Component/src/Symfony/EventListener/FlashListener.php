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

namespace Sylius\Resource\Symfony\EventListener;

use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class FlashListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $requestContextInitiator,
        private FlashHelperInterface $flashHelper,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        $context = $this->requestContextInitiator->initializeContext($request);
        $operation = $this->operationInitiator->initializeOperation($request);

        if (
            null === $operation ||
            $controllerResult instanceof Response ||
            $request->isMethodSafe() ||
            !$request->attributes->getBoolean('is_valid', true)
        ) {
            return;
        }

        $this->flashHelper->addSuccessFlash($operation, $context);
    }
}
