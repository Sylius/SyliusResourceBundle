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

namespace Sylius\Resource\Symfony\Validator\State;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\UpdateOperationInterface;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @experimental
 */
final class ValidateProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $decorated,
        private ValidatorInterface $validator,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $data = $this->decorated->provide($operation, $context);

        $request = $context->get(RequestOption::class)?->request();

        /** @var FormInterface|null $form */
        $form = $request?->attributes->get('form');

        /** @var string $format */
        $format = $request?->getRequestFormat();

        if (
            $data instanceof Response ||
            !($operation instanceof CreateOperationInterface || $operation instanceof UpdateOperationInterface) ||
            !($operation->canValidate() ?? true)
        ) {
            return $data;
        }

        if ('html' !== $format) {
            $validationGroups = $operation->getValidationContext()['groups'] ?? null;
            $violations = $this->validator->validate(value: $data, groups: $validationGroups);
            if (0 !== \count($violations)) {
                throw new ValidationException($violations);
            }

            return $data;
        }

        if (null === $form || null === $request) {
            return $data;
        }

        if (
            !$request->isMethodSafe() &&
            $form->isSubmitted() &&
            $form->isValid()
        ) {
            $request->attributes->set('is_valid', true);
            /** @var array|object|null $data */
            $data = $form->getData();

            return $data;
        }

        $request->attributes->set('is_valid', false);

        return $data;
    }
}
