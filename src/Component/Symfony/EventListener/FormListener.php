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

use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\Symfony\Form\Factory\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class FormListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $contextInitiator,
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();
        $context = $this->contextInitiator->initializeContext($request);
        $operation = $this->operationInitiator->initializeOperation($request);

        /** @var string $format */
        $format = $request->getRequestFormat();

        if (
            $controllerResult instanceof Response ||
            !($operation instanceof CreateOperationInterface || $operation instanceof UpdateOperationInterface) ||
            'html' !== $format ||
            null == $operation->getFormType()
        ) {
            return;
        }

        $form = $this->formFactory->create($operation, $context, $controllerResult);
        $form->handleRequest($request);

        $request->attributes->set('form', $form);
    }
}
