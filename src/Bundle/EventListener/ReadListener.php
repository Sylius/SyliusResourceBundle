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
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Operation\Initiator\RequestOperationInitiator;
use Sylius\Component\Resource\State\ProviderInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadListener
{
    public function __construct(
        private RequestOperationInitiator $operationInitiator,
        private RequestContextInitiator $contextInitiator,
        private ProviderInterface $provider,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $context = $this->contextInitiator->initializeContext($request);

        if (
            (null === $operation = $this->operationInitiator->initializeOperation($request)) ||
            !($operation->canRead() ?? true) ||
            $operation instanceof CreateOperationInterface
        ) {
            return;
        }

        $data = $this->provider->provide($operation, $context);

        if (null === $data) {
            throw new NotFoundHttpException('Not Found');
        }

        $request->attributes->set('data', $data);
    }
}
