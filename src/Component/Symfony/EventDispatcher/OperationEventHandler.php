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

namespace Sylius\Component\Resource\Symfony\EventDispatcher;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class OperationEventHandler implements OperationEventHandlerInterface
{
    public function __construct(private RedirectHandlerInterface $redirectHandler)
    {
    }

    public function handleEvent(
        OperationEvent $event,
        Context $context,
        ?string $newOperation = null,
    ): ?Response {
        $request = $context->get(RequestOption::class)?->request();

        if ('html' !== $request?->getRequestFormat()) {
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

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
}
