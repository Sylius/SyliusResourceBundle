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

namespace Sylius\Resource\Symfony\Form\State;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\BulkOperationInterface;
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\UpdateOperationInterface;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Form\Factory\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @experimental
 */
final class FormProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $decorated,
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $data = $this->decorated->provide($operation, $context);

        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return $data;
        }

        /** @var string $format */
        $format = $request->getRequestFormat();

        if (
            $data instanceof Response ||
            $operation instanceof BulkOperationInterface ||
            !($operation instanceof CreateOperationInterface || $operation instanceof UpdateOperationInterface) ||
            'html' !== $format ||
            null === $operation->getFormType()
        ) {
            return $data;
        }

        $form = $this->formFactory->create($operation, $context, $data);
        $form->handleRequest($request);

        $request->attributes->set('form', $form);

        return $data;
    }
}
