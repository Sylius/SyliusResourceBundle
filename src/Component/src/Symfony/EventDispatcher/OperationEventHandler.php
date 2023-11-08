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

namespace Sylius\Resource\Symfony\EventDispatcher;

use Sylius\Component\Resource\Symfony\Routing\RedirectHandlerInterface;
use Sylius\Component\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class OperationEventHandler implements OperationEventHandlerInterface
{
    public function __construct(
        private RedirectHandlerInterface $redirectHandler,
        private FlashHelperInterface $flashHelper,
    ) {
    }

    public function handlePreProcessEvent(
        OperationEvent $event,
        Context $context,
        ?string $newOperation = null,
    ): ?Response {
        if (!$event->isStopped()) {
            return null;
        }

        $request = $context->get(RequestOption::class)?->request();

        if ('html' !== $request?->getRequestFormat()) {
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $this->flashHelper->addFlashFromEvent($event, $context);

        if (null !== $operationEventResponse = $event->getResponse()) {
            return $operationEventResponse;
        }

        $operation = $event->getOperation();

        if ($operation instanceof HttpOperation && null !== $request) {
            if (null === $newOperation) {
                return $this->redirectHandler->redirectToResource($event->getSubject(), $operation, $request);
            }

            return $this->redirectHandler->redirectToOperation($event->getSubject(), $operation, $request, $newOperation);
        }

        return null;
    }

    public function handlePostProcessEvent(
        OperationEvent $event,
        Context $context,
    ): ?Response {
        $request = $context->get(RequestOption::class)?->request();

        if ('html' !== $request?->getRequestFormat()) {
            return null;
        }

        return $event->getResponse();
    }
}
