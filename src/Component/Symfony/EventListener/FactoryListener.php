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

use Sylius\Component\Resource\Metadata\FactoryAwareOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\State\FactoryInterface;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class FactoryListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $requestContextInitiator,
        private FactoryInterface $factory,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        /** @var (Operation&FactoryAwareOperationInterface)|null $operation */
        $operation = $this->operationInitiator->initializeOperation($request);

        $context = $this->requestContextInitiator->initializeContext($request);

        if (
            !$operation instanceof FactoryAwareOperationInterface ||
            !($operation->getFactory() ?? true)
        ) {
            return;
        }

        $data = $this->factory->create($operation, $context);

        $request->attributes->set('data', $data);
    }
}
