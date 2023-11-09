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
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $contextInitiator,
        private ProviderInterface $provider,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $context = $this->contextInitiator->initializeContext($request);
        $operation = $this->operationInitiator->initializeOperation($request);

        if (
            null === $operation ||
            $operation instanceof CreateOperationInterface ||
            !($operation->canRead() ?? true)
        ) {
            return;
        }

        $data = $this->provider->provide($operation, $context);

        if (null === $data) {
            throw new NotFoundHttpException('Resource has not been found.');
        }

        $request->attributes->set('data', $data);
    }
}
