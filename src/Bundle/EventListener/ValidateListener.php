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

use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Operation\Initiator\RequestOperationInitiator;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class ValidateListener
{
    public function __construct(
        private RequestOperationInitiator $operationRequestInitiator,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var Response|ResourceInterface $controllerResult */
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();
        $form = $request->attributes->get('form');

        if (
            $controllerResult instanceof Response ||
            (null === $operation = $this->operationRequestInitiator->initializeOperation($request)) ||
            !($operation instanceof CreateOperationInterface || $operation instanceof UpdateOperationInterface) ||
            null === $form ||
            !($operation->canValidate() ?? true)
        ) {
            return;
        }

        if (
            ($request->isMethod('POST') || $request->isMethod('PUT')) &&
            $form->isSubmitted() &&
            $form->isValid()
        ) {
            $request->attributes->set('is_valid', true);
            $event->setControllerResult($form->getData());

            return;
        }

        $request->attributes->set('is_valid', false);
    }
}
