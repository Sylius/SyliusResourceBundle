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
use Sylius\Component\Resource\Metadata\Operation\Initiator\RequestOperationInitiator;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Webmozart\Assert\Assert;

final class RespondListener
{
    public function __construct(
        private RequestOperationInitiator $operationRequestInitiator,
        private RequestContextInitiator $contextInitiator,
        private ResponderInterface $responder,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var Response|ResourceInterface $controllerResult */
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();
        $context = $this->contextInitiator->initializeContext($request);

        if (null === $operation = $this->operationRequestInitiator->initializeOperation($request)) {
            return;
        }

        $response = $this->responder->respond($controllerResult, $operation, $context);
        Assert::isInstanceOf($response, Response::class);

        $event->setResponse($response);
    }
}
