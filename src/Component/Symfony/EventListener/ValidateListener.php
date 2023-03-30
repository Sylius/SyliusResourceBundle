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
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidateListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private ValidatorInterface $validator,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $controllerResult = $request->attributes->get('data');

        /** @var FormInterface|null $form */
        $form = $request->attributes->get('form');

        $request = $event->getRequest();
        $operation = $this->operationInitiator->initializeOperation($request);

        /** @var string $format */
        $format = $request->getRequestFormat();

        if (
            $controllerResult instanceof Response ||
            !($operation instanceof CreateOperationInterface || $operation instanceof UpdateOperationInterface) ||
            !($operation->canValidate() ?? true)
        ) {
            return;
        }

        if ('html' !== $format) {
            $validationGroups = $operation->getValidationContext()['groups'] ?? null;
            $violations = $this->validator->validate(value: $controllerResult, groups: $validationGroups);
            if (0 !== \count($violations)) {
                throw new ValidationException($violations);
            }

            return;
        }

        if (null === $form) {
            return;
        }

        if (
            !$request->isMethodSafe() &&
            $form->isSubmitted() &&
            $form->isValid()
        ) {
            $request->attributes->set('is_valid', true);
            $request->attributes->set('data', $form->getData());

            return;
        }

        $request->attributes->set('is_valid', false);
    }
}
