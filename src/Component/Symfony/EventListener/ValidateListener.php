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

namespace Sylius\Component\Resource\Symfony\EventListener;

use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class ValidateListener
{
    public function __construct(
        private HttpOperationInitiator $operationInitiator,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        /** @var FormInterface|null $form */
        $form = $request->attributes->get('form');

        $request = $event->getRequest();
        $operation = $this->operationInitiator->initializeOperation($request);

        if (
            $controllerResult instanceof Response ||
            !($operation instanceof CreateOperationInterface || $operation instanceof UpdateOperationInterface) ||
            null === $form ||
            !($operation->canValidate() ?? true)
        ) {
            return;
        }

        if (
            !$request->isMethodSafe() &&
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
